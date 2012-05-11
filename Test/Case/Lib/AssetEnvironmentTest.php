<?php 
App::uses('AssetEnvironment', 'Asset.Lib');

class AssetEnvironmentTest extends CakeTestCase {
	public function setUp() {
		$this->path = App::pluginPath('Asset') . 'Test' . DS . 'test_app' . DS . 'webroot' . DS;
		$this->AssetEnvironment = new AssetEnvironment($this->path);
	}

	public function testSingleton() {
		$this->assertInstanceOf('AssetEnvironment', AssetEnvironment::getInstance());
	}

	public function testGetInstanceWithPath() {
		$result = AssetEnvironment::getInstance($this->path);
		$this->assertInstanceOf('AssetEnvironment', $result);
		$this->assertNotSame(AssetEnvironment::getInstance(), $result);
		$this->assertEquals($this->path, $result->webroot);
	}

	public function testGetInstanceWithInstance() {
		$instance = AssetEnvironment::getInstance($this->path);
		$result = AssetEnvironment::getInstance($instance);
		$this->assertInstanceOf('AssetEnvironment', $result);
		$this->assertSame($instance, $result);
		$this->assertNotSame(AssetEnvironment::getInstance(), $result);
	}

/**
 * @dataProvider resolveProvider
 */
	public function testResolve($url, $expected) {
		$this->assertEquals($expected, $this->AssetEnvironment->resolve($url));
	}

	public function resolveProvider() {
		$path = App::pluginPath('Asset') . 'Test' . DS . 'test_app' . DS . 'webroot' . DS;
		return array(
			array('css/bundle.css', $path . 'css' . DS . 'bundle.css'),
			array('css/default.css', $path . 'css' . DS . 'default.css'),
			array('css/app/bundle.css', $path . 'css' . DS . 'app' . DS . 'bundle.css')
		);
	}

/**
 * @expectedException InvalidArgumentException
 */
	public function testResolveInvalid() {
		$this->AssetEnvironment->resolve('invalid.css');
	}
}