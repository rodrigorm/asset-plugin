<?php 
App::uses('Asset', 'Asset.Lib');
App::uses('AssetContext', 'Asset.Lib');
App::uses('AssetSprocketsFilter', 'Asset.Filter');

class AssetProcessor {
	public $asset;
	public $context;

	public function __construct(Asset $asset, AssetContext $context = null) {
		$this->asset = $asset;
		$this->context = $context;
		if (is_null($this->context)) {
			$this->context = new AssetContext($asset->env);
		}
	}

	public function content() {
		return $this->_content($this->asset);
	}

	protected function _content($asset) {
		return $this->_process(file_get_contents($asset->file));
	}

	protected function _process($content) {
		$filter = new AssetSprocketsFilter($this);
		return $filter->input($content);
	}

	public function requireAsset($asset) {
		return $this->_require($this->context->load($asset, $this->asset));
	}

	public function _require($asset) {
		if (Configure::read('debug') == 0) {
			return $asset->content($this->context) . "\n";
		}
		return $asset->import($this->context) . "\n";
	}
}