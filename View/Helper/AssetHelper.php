<?php
App::uses('Helper', 'View');
App::uses('Asset', 'Asset.Lib');

class AssetHelper extends Helper {
	public function assetTimestamp($path) {
		return Asset::fromUrl($path)->digestUrl();
	}
}