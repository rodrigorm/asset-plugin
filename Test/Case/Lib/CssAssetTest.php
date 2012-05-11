<?php 
App::uses('CssAsset', 'Asset.Lib');

class CssAssetTest extends CakeTestCase {
	public function setUp() {
		$this->path = App::pluginPath('Asset') . 'Test' . DS . 'test_app' . DS . 'webroot' . DS;
		$this->file = $this->path . 'css' . DS . 'default.css';
		$this->Asset = new CssAsset($this->file);
	}

	public function testConstruct() {
		$this->assertEquals($this->file, $this->Asset->file);
	}

/**
 * @expectedException InvalidArgumentException
 */
	public function testConstructInvalidFile() {
		new CssAsset($this->path . 'photo.jpg');
	}

	public function testContent() {
		$expected = 'body {}';
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testFromUrl() {
		$result = CssAsset::fromUrl('css/bundle.css', $this->path);
		$this->assertInstanceOf('CssAsset', $result);
		$this->assertEquals($this->path . 'css' . DS . 'bundle.css', $result->file);
	}
}