<?php 
App::uses('AssetEnvironment', 'Asset.Lib');

class AssetDispatcher {
	public function dispatch($url, CakeResponse $response) {
		$path = preg_replace('#c(css|js)\/#', '$1/', $url);

		$pathSegments = explode('.', $path);
		$ext = array_pop($pathSegments);
		$parts = explode('/', $path);

		try {
			$this->_deliver($response, AssetEnvironment::resolve($path), $ext);
		} catch (Exception $e) {
			$response->statusCode(404);
			$response->send();
		}
		return;
	}

/**
 * Sends an asset file to the client
 *
 * @param CakeResponse $response The response object to use.
 * @param string $assetFile Path to the asset file in the file system
 * @param string $ext The extension of the file to determine its mime type
 * @return void
 */
	protected function _deliver(CakeResponse $response, $assetFile, $ext) {
		ob_start();
		$compressionEnabled = Configure::read('Asset.compress') && $response->compress();
		if ($response->type($ext) == $ext) {
			$contentType = 'application/octet-stream';
			$agent = env('HTTP_USER_AGENT');
			if (preg_match('%Opera(/| )([0-9].[0-9]{1,2})%', $agent) || preg_match('/MSIE ([0-9].[0-9]{1,2})/', $agent)) {
				$contentType = 'application/octetstream';
			}
			$response->type($contentType);
		}
		if (!$compressionEnabled) {
			$response->header('Content-Length', filesize($assetFile));
		}
		$response->cache(filemtime($assetFile));
		$response->send();
		ob_clean();
		if ($ext === 'css' || $ext === 'js') {
			include $assetFile;
		} else {
			readfile($assetFile);
		}

		if ($compressionEnabled) {
			ob_end_flush();
		}
	}
}