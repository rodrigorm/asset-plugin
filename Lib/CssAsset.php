<?php 
App::uses('Asset', 'Asset.Lib');

class CssAsset extends Asset {
	public function import(AssetContext $context = null) {
		return '@import "/asset/' . $this->digestUrl($context) . '";';
	}
}