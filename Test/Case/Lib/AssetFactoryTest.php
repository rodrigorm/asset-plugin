<?php 
App::uses('AssetFactory', 'Asset.Lib');

class AssetFactoryTest extends CakeTestCase {
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
		$this->file = $this->path . 'css' . DS . 'default.css';
	}

	public function testFromUrl() {
		$result = AssetFactory::fromUrl('css/bundle.css', $this->path);
		$this->assertInstanceOf('CssAsset', $result);

		$result = AssetFactory::fromUrl('js/bundle.js', $this->path);
		$this->assertInstanceOf('JsAsset', $result);

		$result = AssetFactory::fromUrl('img/photo.jpg', $this->path);
		$this->assertInstanceOf('StaticAsset', $result);
	}

	public function testFromAsset() {
		$Asset = new CssAsset('css/default.css', $this->file, $this->path);
		$result = AssetFactory::fromAsset($Asset, 'bundle');
		$this->assertInstanceOf('Asset', $result);
		$this->assertEquals('css/bundle.css', $result->url);
		$this->assertEquals($this->path . 'css' . DS . 'bundle.css', $result->file);

		$Asset = new CssAsset('css/app/bundle.css', $this->file, $this->path);
		$result = AssetFactory::fromAsset($Asset, '../bundle');
		$this->assertInstanceOf('Asset', $result);
		$this->assertEquals('css/bundle.css', $result->url);
		$this->assertEquals($this->path . 'css' . DS . 'bundle.css', $result->file);

		$Asset = new CssAsset('css/default.css', $this->file, $this->path);
		$result = AssetFactory::fromAsset($Asset, 'app/bundle');
		$this->assertInstanceOf('Asset', $result);
		$this->assertEquals('css/app/bundle.css', $result->url);
		$this->assertEquals($this->path . 'css' . DS . 'app' . DS . 'bundle.css', $result->file);

		$result = AssetFactory::fromAsset($Asset, '/theme/admin/css/bundle');
		$this->assertInstanceOf('Asset', $result);
		$this->assertEquals('theme/admin/css/bundle.css', $result->url);
		$this->assertEquals(App::themePath('admin') . 'webroot' . DS . 'css' . DS . 'bundle.css', $result->file);
	}
}