<?php 
App::uses('Asset', 'Asset.Lib');

class AssetTest extends CakeTestCase {
	public function setUp() {
		$this->path = App::pluginPath('Asset') . 'Test' . DS . 'test_app' . DS . 'webroot' . DS;
		$this->file = $this->path . 'css' . DS . 'default.css';
		$this->Asset = new Asset('css/default.css', $this->file);
	}

	public function testConstruct() {
		$this->assertEquals($this->file, $this->Asset->file);
	}

	public function testDigest() {
		$this->assertEquals('fcdce6b6d6e2175f6406869882f6f1ce', $this->Asset->digest());
	}

	public function testDigestUrl() {
		$this->assertEquals('css/default-fcdce6b6d6e2175f6406869882f6f1ce.css', $this->Asset->digestUrl());
	}

	public function testContent() {
		$expected = 'body {}';
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testFromUrl() {
		$result = Asset::fromUrl('css/bundle.css', $this->path);
		$this->assertInstanceOf('Asset', $result);
		$this->assertEquals($this->path . 'css' . DS . 'bundle.css', $result->file);
	}
}