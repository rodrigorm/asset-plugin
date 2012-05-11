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

	public function resolve($asset, $relative = null) {
		if (($result = $this->_webroot($asset))) {
			return $result;
		}

		if (($result = $this->_theme($asset))) {
			return $result;
		}

		if (($result = $this->_plugin($asset))) {
			return $result;
		}

		throw new InvalidArgumentException(__('Could not locate asset: %s', $asset));
	}

	protected function _webroot($asset) {
		return $this->_exists(realpath($this->webroot . str_replace('/', DS, $asset)));
	}

	protected function _theme($asset) {
		$parts = explode('/', $asset);

		if ($parts[0] !== 'theme') {
			return false;
		}

		$themeName = $parts[1];
		unset($parts[0], $parts[1]);
		$fileFragment = implode(DS, $parts);
		$path = App::themePath($themeName) . 'webroot' . DS;
		return $this->_exists($path . $fileFragment);
	}

	protected function _plugin($asset) {
		$parts = explode('/', $asset);

		$plugin = Inflector::camelize($parts[0]);
		if (!CakePlugin::loaded($plugin)) {
			return false;
		}

		unset($parts[0]);
		$fileFragment = implode(DS, $parts);
		$path = CakePlugin::path($plugin) . 'webroot' . DS;
		return $this->_exists($path . $fileFragment);
	}

	protected function _exists($filename) {
		if (file_exists($filename)) {
			return realpath($filename);
		}
		return false;
	}
}