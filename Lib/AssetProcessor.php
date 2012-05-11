<?php 
App::uses('Asset', 'Asset.Lib');
App::uses('AssetContext', 'Asset.Lib');

class AssetProcessor {
	protected $_asset;
	protected $_pattern = '/^\s?\/\/\=\s+require\s+(\"?)([^\"\n]+)\1\n+/m';
	protected $_context;

	public function __construct(Asset $asset, AssetContext $context = null) {
		$this->_asset = $asset;
		$this->_context = $context;
		if (is_null($this->_context)) {
			$this->_context = new AssetContext($asset->env);
		}
	}

	public function content() {
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
		$asset = $this->_context->load($required, $this->_asset);
		return $asset->content($this->_context) . "\n";
	}
}