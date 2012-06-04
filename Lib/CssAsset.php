<?php 
App::uses('Asset', 'Asset.Lib');

class CssAsset extends Asset {
	public function import(Asset $asset, AssetContext $context = null) {
		return '@import "/asset/' . $asset->digestUrl($context) . '";';
	}
}