<?php 
class AssetBootstrapTest extends CakeTestCase {
	public function testFilterCss() {
		$this->assertStringEndsWith('Asset/webroot/asset.php', Configure::read('Asset.filter.css'));
		$this->assertStringEndsWith('Asset/webroot/asset.php', Configure::read('Asset.filter.js'));
	}
}