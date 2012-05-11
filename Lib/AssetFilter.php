<?php 
App::uses('AssetProcessor', 'Asset.Lib');
App::uses('Asset', 'Asset.Lib');

abstract class AssetFilter {
	protected $_processor;

	public function __construct(AssetProcessor $processor) {
		$this->_processor = $processor;
	}
}

interface AssetInputFilter {
	public function input($content);
}

interface AssetContentFilter {
	public function content($content);
}