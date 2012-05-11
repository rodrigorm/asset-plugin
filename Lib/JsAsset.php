<?php 
App::uses('Asset', 'Asset.Lib');

class JsAsset extends Asset {
	public function import(AssetContext $context = null) {
		return 'document.write(\'<script src="/' . $this->digestUrl($context) . '"></script>\');';
	}
}