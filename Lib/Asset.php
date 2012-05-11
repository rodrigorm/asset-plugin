<?php 
App::uses('AssetEnvironment', 'Asset.Lib');
App::uses('AssetProcessor', 'Asset.Lib');
App::uses('AssetContext', 'Asset.Lib');

class Asset {
	public $url;
	public $file;
	public $env;
	protected $_content = null;

	static public function fromUrl($url, $env = null) {
		$url = preg_replace('#^(.+)-([\da-f]+)\.(\w+)$#', '$1.$3', $url);
		$asset = preg_replace('#c(css|js)\/#', '$1/', $url);
		$environment = AssetEnvironment::getInstance($env);
		$file = $environment->resolve($asset);
		if (!file_exists($file)) {
			throw new InvalidArgumentException(__d('asset', 'Invalid filename: %s', $file));
			
		}
		return new Asset($url, $file, $environment);
	}

	static public function fromAsset($relative, $url) {
		$info = pathinfo($url);
		if (empty($info['extension'])) {
			$info['extension'] = $relative->extension();
		}
		$info['dirname'] .= '/';

		$asset = $info['dirname'] . $info['basename'] . '.' . $info['extension'];
		if (substr($asset, 0, 1) !== '/') {
			$asset = $relative->dirname() . '/' . $asset;
		}
		$asset = preg_replace('/\w+\/\.\.\//', '', $asset);
		$asset = str_replace('./', '', $asset);
		$asset = preg_replace('#\/{2,}#', '/', $asset);
		$asset = preg_replace('#^\/#', '', $asset);
		return Asset::fromUrl($asset, $relative->env);
	}

	public function __construct($url, $file, $env = null) {
		$this->url = $url;
		$this->file = $file;
		$this->env = AssetEnvironment::getInstance($env);
	}

	public function digest() {
		return md5($this->content());
	}

	public function digestUrl() {
		$parts = explode('.', $this->url);
		$extension = array_pop($parts);
		return implode('.', $parts) . '-' . $this->digest() . '.' . $extension;
	}

	public function content(AssetContext $context = null) {
		if (is_null($this->_content)) {
			$this->_content = $this->_process($context);
		}
		return $this->_content;
	}

	protected function _process(AssetContext $context = null) {
		if (is_null($context)) {
			$context = new AssetContext($this->env);
			$context->depend($this);
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