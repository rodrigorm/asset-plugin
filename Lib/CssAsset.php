<?php 
App::uses('AssetEnvironment', 'Asset.Lib');

class CssAsset {
	public $file;
	protected $_env;

	static public function fromUrl($url, $env = null) {
		$environment = AssetEnvironment::getInstance($env);
		return new CssAsset($environment->resolve($url), $environment);
	}

	public function __construct($file, $env = null) {
		$pathSegments = explode('.', $file);
		$ext = array_pop($pathSegments);
		if ($ext !== 'css') {
			throw new InvalidArgumentException(__('Invalid CssAsset file: %s', $file));
		}

		$this->file = $file;

		$this->_env = AssetEnvironment::getInstance($env);
	}

	public function content() {
		return file_get_contents($this->file);
	}
}