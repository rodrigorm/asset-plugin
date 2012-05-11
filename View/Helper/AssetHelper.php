<?php
App::uses('Helper', 'View');
App::uses('Asset', 'Asset.Lib');

class AssetHelper extends Helper {
	public function assetTimestamp($path) {
		$asset = Asset::fromUrl($path);
		return $asset->digestUrl();
	}
}