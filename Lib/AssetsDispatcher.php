<?php 
App::uses('AssetFactory', 'Asset.Lib');
App::uses('File', 'Utility');

class AssetsDispatcher {
	protected $_env;

	public function __construct($env = null) {
		$this->_env = $env;
	}

	public function dispatch($url, CakeResponse $response) {
		try {
			$asset = AssetFactory::fromUrl($url, $this->_env);
			$this->_deliver($response, $asset);
			if (Configure::read('debug') == 0) {
				$File = new File(WWW_ROOT . 'asset' . DS . str_replace('/', DS, $asset->digestUrl()), true);
				$File->write($asset->content());
			}
		} catch (Exception $e) {
			$response->statusCode(404);
			$response->send();
		}
		return;
	}

	protected function _deliver(CakeResponse $response, Asset $asset) {
		ob_start();
		$compressionEnabled = Configure::read('Asset.compress') && $response->compress();
		if ($response->type($asset->extension()) == $asset->extension()) {
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