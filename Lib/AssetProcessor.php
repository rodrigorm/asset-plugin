<?php 
App::uses('AssetEnvironment', 'Asset.Lib');

class AssetProcessor {
	protected $_asset;
	protected $_env;
	protected $_pattern = '/^\s?\/\/\=\s+require\s+(\"?)([^\"\>]+)\1\n+/m';
	protected $_loaded = array();


	public function __construct(Asset $asset, $env = null) {
		$this->_asset = $asset;
		$this->_env = AssetEnvironment::getInstance($env);
	}

	public function content() {
		$this->_loaded = array();
		return $this->_content($this->_asset);
	}

	protected function _content($asset) {
		return $this->_process($asset->file, file_get_contents($asset->file));
	}

	protected function _process($filename, $content) {
		return preg_replace_callback(
			$this->_pattern,
			array($this, '_replace'),
			$content
		);
	}

	protected function _replace($matches) {
		$required = $matches[2];
		$asset = $this->_asset->resolve($required);

		if (empty($this->_loaded[$asset->file])) {
			$this->_loaded[$asset->file] = true;
			return $asset->content() . "\n";
		}
		return '';
	}
}