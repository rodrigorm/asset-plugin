<?php 
App::uses('AssetEnvironment', 'Asset.Lib');
App::uses('AssetProcessor', 'Asset.Lib');

class Asset {
	public $url;
	public $file;
	protected $_env;

	static public function fromUrl($url, $env = null) {
		$environment = AssetEnvironment::getInstance($env);
		return new Asset($url, $environment->resolve($url), $environment);
	}

	public function __construct($url, $file, $env = null) {
		if (!file_exists($file)) {
			throw new InvalidArgumentException(__d('asset', 'Invalid filename: %s', $file));
			
		}
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
		$processor = new AssetProcessor($this, $this->_env);
		return $processor->content();
	}

	public function size() {
		return strlen($this->content());
	}

	public function resolve($dependency) {
		$info = pathinfo($dependency);
		if (empty($info['extension'])) {
			$info['extension'] = $this->extension();
		}
		$info['dirname'] .= '/';

		$asset = $info['dirname'] . $info['basename'] . '.' . $info['extension'];
		$asset = preg_replace('/\w+\/\.\.\//', '', $this->dirname() . '/' . $asset);
		$asset = str_replace('./', '', $asset);
		return Asset::fromUrl($asset, $this->_env);
	}

	public function dirname() {
		return $this->_pathinfo('dirname');
	}

	public function extension() {
		return $this->_pathinfo('extension');
	}

	protected function _pathinfo($key) {
		$info = pathinfo($this->url);
		return $info[$key];
	}
}