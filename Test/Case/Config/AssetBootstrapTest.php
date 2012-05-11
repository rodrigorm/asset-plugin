<?php 
class AssetBootstrapTest extends CakeTestCase {
	public function testFilterCss() {
		CakePlugin::loadAll(array('Asset' => array('bootstrap' => true)));
		$this->assertStringEndsWith('Asset/webroot/asset.php', Configure::read('Asset.filter.css'));
		$this->assertStringEndsWith('Asset/webroot/asset.php', Configure::read('Asset.filter.js'));
	}
}