<?php 
App::uses('AssetProcessor', 'Asset.Lib');
App::uses('AssetEnvironment', 'Asset.Lib');
App::uses('Asset', 'Asset.Lib');

class AssetProcessorTest extends CakeTestCase {
	public function setUp() {
		$this->Env = AssetEnvironment::getInstance(App::pluginPath('Asset') . DS . 'Test' . DS . 'test_app' . DS . 'webroot' . DS);
		$this->Asset = Asset::fromUrl('css/bundle.css', $this->Env);
		$this->Processor = new AssetProcessor($this->Asset, $this->Env);
	}

	public function testContent() {
		$expected = <<<EOT
body { /* default.css */ }
body { /* bundle.css */ }
EOT;
		$this->assertEquals($expected, $this->Processor->content());
	}

	public function testContentDepth() {
		$this->Asset = Asset::fromUrl('css/app/bundle.css', $this->Env);
		$this->Processor = new AssetProcessor($this->Asset, $this->Env);
		$expected = <<<EOT
body { /* default.css */ }
body { /* bundle.css */ }
body { /* app/bundle.css */ }
EOT;
		$this->assertEquals($expected, $this->Processor->content());
	}
}