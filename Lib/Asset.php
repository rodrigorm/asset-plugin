<?php 
App::uses('AssetEnvironment', 'Asset.Lib');
App::uses('AssetProcessor', 'Asset.Lib');
App::uses('AssetContext', 'Asset.Lib');
App::uses('AssetFactory', 'Asset.Lib');

abstract class Asset {
	public $url;
	public $file;
	public $env;
	protected $_content = null;

	static public function fromUrl($url, $env = null) {
		$asset = preg_replace('#^(.+)-([\da-f]+)\.(\w+)$#', '$1.$3', urldecode($url));
		$environment = AssetEnvironment::getInstance($env);
		$file = $environment->resolve($asset);
		if (!file_exists($file)) {
			throw new InvalidArgumentException(__d('asset', 'Invalid filename: %s', $file));
		}

		return new static($url, $file, $environment);
	}

	public function __construct($url, $file, $env = null) {
		$this->url = $url;
		$this->file = $file;
		$this->env = AssetEnvironment::getInstance($env);
	}

	public function digest(AssetContext $context = null) {
		return md5($this->content($context));
	}

	public function digestUrl(AssetContext $context = null) {
		$parts = explode('.', $this->url);
		$extension = array_pop($parts);
		return implode('.', $parts) . '-' . $this->digest($context) . '.' . $extension;
	}

	public function content(AssetContext $context = null) {
		if (is_null($this->_content)) {
			$this->_content = $this->_process($context);
		}
		return $this->_content;
	}

	public function import(AssetContext $context = null) {
		return $this->content($context);
	}

	protected function _process(AssetContext $context = null) {
		if (is_null($context)) {
			$context = new AssetContext($this->env);
			$context->depends($this);
		}
		$processor = new AssetProcessor($this, $context);
		return $processor->content();
	}

	public function size() {
		return strlen($this->content());
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

class NullAsset extends Asset {
	public function content() {
		return '';
	}
}