<?php 
App::uses('AssetEnvironment', 'Asset.Lib');
App::uses('AssetProcessor', 'Asset.Lib');
App::uses('AssetContext', 'Asset.Lib');

class Asset {
	public $url;
	public $file;
	public $env;

	static public function fromUrl($url, $env = null) {
		$environment = AssetEnvironment::getInstance($env);
		return new Asset($url, $environment->resolve($url), $environment);
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
		if (!file_exists($file)) {
			throw new InvalidArgumentException(__d('asset', 'Invalid filename: %s', $file));
			
		}
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