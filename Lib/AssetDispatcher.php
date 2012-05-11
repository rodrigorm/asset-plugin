<?php 
App::uses('Asset', 'Asset.Lib');

class AssetDispatcher {
	protected $_env;

	public function __construct($env = null) {
		$this->_env = $env;
	}

	public function dispatch($url, CakeResponse $response) {
		$path = preg_replace('#c(css|js)\/#', '$1/', $url);

		$pathSegments = explode('.', $path);
		$ext = array_pop($pathSegments);

		try {
			$this->_deliver($response, Asset::fromUrl($path, $this->_env), $ext);
		} catch (Exception $e) {
			$response->statusCode(404);
			$response->send();
		}
		return;
	}

	protected function _deliver(CakeResponse $response, $asset, $ext) {
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
			$response->header('Content-Length', $asset->size());
		}
		$response->cache(filemtime($asset->file));
		$response->send();
		ob_clean();
		echo $asset->content();
		if ($compressionEnabled) {
			ob_end_flush();
		}
	}
}