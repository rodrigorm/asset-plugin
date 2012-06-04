<?php 
App::uses('Asset', 'Asset.Lib');

class JsAsset extends Asset {
	public function import(Asset $asset, AssetContext $context = null) {
		return 'document.write(\'<script src="/asset/' . $asset->digestUrl($context) . '"></script>\');';
	}
}