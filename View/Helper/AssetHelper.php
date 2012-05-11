<?php
App::uses('Helper', 'View');
App::uses('AssetFactory', 'Asset.Lib');

class AssetHelper extends Helper {
	public $env = WWW_ROOT;

	public function assetTimestamp($path) {
		try {
			return '/asset' . AssetFactory::fromUrl($path, $this->env)->digestUrl();
		} catch (Exception $e) {
			return parent::assetTimestamp($path);
		}
	}
}