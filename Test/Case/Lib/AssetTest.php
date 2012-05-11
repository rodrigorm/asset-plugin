<?php 
App::uses('Asset', 'Asset.Lib');

class AssetTest extends CakeTestCase {
	public function setUp() {
		$this->path = App::pluginPath('Asset') . 'Test' . DS . 'test_app' . DS . 'webroot' . DS;
		$this->file = $this->path . 'css' . DS . 'default.css';
		$this->Asset = new Asset('css/default.css', $this->file, $this->path);
	}

	public function testConstruct() {
		$this->assertEquals($this->file, $this->Asset->file);
	}

	public function testDigest() {
		$this->assertEquals('42dcbd72dd658306b48c1161ae1643fc', $this->Asset->digest());
	}

	public function testDigestBundle() {
		$this->Asset = Asset::fromUrl('css/bundle.css', $this->path);
		$this->assertEquals('40399219e34ffc6160525ae2c29e3c00', $this->Asset->digest());
	}

	public function testDigestUrl() {
		$this->assertEquals('css/default-42dcbd72dd658306b48c1161ae1643fc.css', $this->Asset->digestUrl());
	}

	public function testContent() {
		$expected = 'body { /* default.css */ }';
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testFromUrl() {
		$result = Asset::fromUrl('css/bundle.css', $this->path);
		$this->assertInstanceOf('Asset', $result);
		$this->assertEquals($this->path . 'css' . DS . 'bundle.css', $result->file);
	}

	public function testResolve() {
		$Asset = new Asset('css/default.css', $this->file, $this->path);
		$result = $Asset->resolve('bundle');
		$this->assertInstanceOf('Asset', $result);
		$this->assertEquals('css/bundle.css', $result->url);
		$this->assertEquals($this->path . 'css' . DS . 'bundle.css', $result->file);

		$Asset = new Asset('css/app/bundle.css', $this->file, $this->path);
		$result = $Asset->resolve('../bundle');
		$this->assertInstanceOf('Asset', $result);
		$this->assertEquals('css/bundle.css', $result->url);
		$this->assertEquals($this->path . 'css' . DS . 'bundle.css', $result->file);

		$Asset = new Asset('css/default.css', $this->file, $this->path);
		$result = $Asset->resolve('app/bundle');
		$this->assertInstanceOf('Asset', $result);
		$this->assertEquals('css/app/bundle.css', $result->url);
		$this->assertEquals($this->path . 'css' . DS . 'app' . DS . 'bundle.css', $result->file);
	}

	public function testSize() {
		$this->assertEquals(26, $this->Asset->size());
	}
}