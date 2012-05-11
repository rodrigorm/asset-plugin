<?php
App::uses('Helper', 'View');
App::uses('AssetTimestamper', 'Asset.Lib');

class AssetHelper extends Helper {
	public function assetTimestamp($path) {
		$stamp = Configure::read('Asset.timestamp');
		$timestampEnabled = $stamp !== false;

		if ($timestampEnabled && strpos($path, '?') === false) {
			$filepath = preg_replace('/^' . preg_quote($this->request->webroot, '/') . '/', '', $path);
			$webrootPath = WWW_ROOT . str_replace('/', DS, $filepath);
			if (file_exists($webrootPath)) {
				return AssetTimestamper::timestamp($path, $webrootPath);
			}
			$segments = explode('/', ltrim($filepath, '/'));
			if ($segments[0] === 'theme') {
				$theme = $segments[1];
				unset($segments[0], $segments[1]);
				$themePath = App::themePath($theme) . 'webroot' . DS . implode(DS, $segments);
				return AssetTimestamper::timestamp($path, $themePath);
			} else {
				$plugin = Inflector::camelize($segments[0]);
				if (CakePlugin::loaded($plugin)) {
					unset($segments[0]);
					$pluginPath = CakePlugin::path($plugin) . 'webroot' . DS . implode(DS, $segments);
					return AssetTimestamper::timestamp($path, $pluginPath);
				}
			}
		}
		return parent::assetTimestamp($path);
	}
}