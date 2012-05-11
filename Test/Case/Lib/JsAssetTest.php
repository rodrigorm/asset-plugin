<?php 
App::uses('JsAsset', 'Asset.Lib');

class JsAssetTest extends CakeTestCase {
	public function setUp() {
		App::build(array(
			'View' => array(
				App::pluginPath('Asset') . 'Test' . DS . 'test_app' . DS . 'View' . DS
			),
			'Plugin' => array(
				App::pluginPath('Asset') . 'Test' . DS . 'test_app' . DS . 'Plugin' . DS
			)
		), App::RESET);
		$this->path = App::pluginPath('Asset') . 'Test' . DS . 'test_app' . DS . 'webroot' . DS;
		$this->file = $this->path . 'js' . DS . 'default.js';
		$this->Asset = new JsAsset('js/default.js', $this->file, $this->path);
	}

	public function testContent() {
		$expected = 'var Default = {};';
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentBundle() {
		$this->Asset = JsAsset::fromUrl('js/bundle.js', $this->path);
		$expected = <<<EOT
document.write('<script src="/asset/js/default-e4d6bff70072c8553794a4ba4daca1de.js"></script>');
var Bundle = {};
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}
}