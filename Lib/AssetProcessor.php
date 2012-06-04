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
		return $this->_process(file_get_contents($this->asset->file));
	}

	protected function _process($content) {
		$filter = new AssetSprocketsFilter($this);
		return $filter->input($content);
	}

	public function importAsset($asset) {
		return $this->import($this->context->depend($asset, $this->asset));
	}

	public function import($asset) {
		if (Configure::read('debug') == 0) {
			return $asset->content($this->context);
		}
		return $asset->import($this->context);
	}
}