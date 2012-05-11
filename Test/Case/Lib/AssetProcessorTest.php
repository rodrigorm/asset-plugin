<?php 
App::uses('AssetProcessor', 'Asset.Lib');
App::uses('AssetEnvironment', 'Asset.Lib');
App::uses('Asset', 'Asset.Lib');
App::uses('AssetContext', 'Asset.Lib');

class AssetProcessorTest extends CakeTestCase {
	public function setUp() {
		$this->Env = AssetEnvironment::getInstance(App::pluginPath('Asset') . DS . 'Test' . DS . 'test_app' . DS . 'webroot' . DS);
		$this->Asset = AssetFactory::fromUrl('css/bundle.css', $this->Env);
		$this->Context = $this->getMock('AssetContext');
		$this->Processor = new AssetProcessor($this->Asset, $this->Context);
	}

	public function testRequireAsset() {
		$asset = $this->getMock('Asset', array(), array('url', 'file'));
		$this->Context->expects($this->once())
			->method('load')
			->will($this->returnValue($asset));
		$asset->expects($this->once())
			->method('import')
			->with($this->Processor->context);
		$this->Processor->requireAsset($asset);
	}

	public function testRequireAssetCombine() {
		$debug = Configure::read('debug');
		Configure::write('debug', 0);
		$asset = $this->getMock('Asset', array(), array('url', 'file'));
		$this->Context->expects($this->once())
			->method('load')
			->will($this->returnValue($asset));
		$asset->expects($this->once())
			->method('content')
			->with($this->Processor->context);
		$this->Processor->requireAsset($asset);
		Configure::write('debug', $debug);
	}
}