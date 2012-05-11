<?php 
App::uses('AssetEnvironment', 'Asset.Lib');

class Asset {
	public $url;
	public $file;
	protected $_env;

	static public function fromUrl($url, $env = null) {
		$environment = AssetEnvironment::getInstance($env);
		return new Asset($url, $environment->resolve($url), $environment);
	}

	public function __construct($url, $file, $env = null) {
		$this->url = $url;
		$this->file = $file;
		$this->_env = AssetEnvironment::getInstance($env);
	}

	public function digest() {
		return md5($this->content());
	}

	public function digestUrl() {
		$parts = explode('.', $this->url);
		$extension = array_pop($parts);
		return implode('.', $parts) . '-' . $this->digest() . '.' . $extension;
	}

	public function content() {
		return file_get_contents($this->file);
	}
}