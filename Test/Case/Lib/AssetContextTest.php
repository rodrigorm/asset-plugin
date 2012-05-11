<?php 
App::uses('AssetContext', 'Asset.Lib');

class AssetContextTest extends CakeTestCase {
	public function setUp() {
		$this->path = App::pluginPath('Asset') . 'Test' . DS . 'test_app' . DS . 'webroot' . DS;
		$this->Context = new AssetContext($this->path);
	}

	public function testResolve() {
		$result = $this->Context->resolve('css/bundle.css');
		$this->assertEquals($this->path . 'css' . DS . 'bundle.css', $result->file);
	}

	public function testResolveRelative() {
		$relative = $this->Context->resolve('css/default.css');
		$result = $this->Context->resolve('bundle', $relative);
		$this->assertEquals($this->path . 'css' . DS . 'bundle.css', $result->file);
	}
}