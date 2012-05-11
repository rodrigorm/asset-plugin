<?php 
class AssetEnvironment {
	public $webroot;

	public static function &getInstance($webroot = null) {
		if (is_string($webroot)) {
			$result = new AssetEnvironment($webroot);
			return $result;
		}

		if (is_a($webroot, 'AssetEnvironment')) {
			return $webroot;
		}

		static $instance = array();
		if (!$instance) {
			$instance[0] = new AssetEnvironment(WWW_ROOT);
		}
		return $instance[0];
	}

	public function __construct($webroot) {
		$this->webroot = $webroot;
	}

	public function resolve($url) {
		$parts = explode('/', $url);
		$assetFile = null;

		$webrootPath = $this->webroot . str_replace('/', DS, $url);
		if (file_exists($webrootPath)) {
			return $webrootPath;
		}

		if ($parts[0] === 'theme') {
			$themeName = $parts[1];
			unset($parts[0], $parts[1]);
			$fileFragment = urldecode(implode(DS, $parts));
			$path = App::themePath($themeName) . 'webroot' . DS;
			if (file_exists($path . $fileFragment)) {
				return $path . $fileFragment;
			}
		} else {
			$plugin = Inflector::camelize($parts[0]);
			if (CakePlugin::loaded($plugin)) {
				unset($parts[0]);
				$fileFragment = urldecode(implode(DS, $parts));
				$pluginWebroot = CakePlugin::path($plugin) . 'webroot' . DS;
				if (file_exists($pluginWebroot . $fileFragment)) {
					return $pluginWebroot . $fileFragment;
				}
			}
		}

		throw new InvalidArgumentException(__('Could not locate asset: %s', $url));
	}
}