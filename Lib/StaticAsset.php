<?php 
App::uses('Asset', 'Asset.Lib');

class StaticAsset extends Asset {
	public function content(AssetContext $context = null) {
		return file_get_contents($this->file);
	}
}