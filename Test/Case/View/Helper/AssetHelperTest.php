<?php 
App::uses('AssetHelper', 'Asset.View/Helper');
App::uses('View', 'View');

class AssetHelperTest extends CakeTestCase {
	public function setUp() {
		$this->Helper = new AssetHelper(new View());
		$this->Helper->env = App::pluginPath('Asset') . 'Test' . DS . 'test_app' . DS . 'webroot' . DS;
	}

	public function testAssetTimestamp() {
		$result = $this->Helper->assetTimestamp('/css/default.css');
		$this->assertEquals('/css/default-42dcbd72dd658306b48c1161ae1643fc.css', $result);
	}
}