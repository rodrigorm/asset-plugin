<?php 
App::uses('Asset', 'Asset.Lib');

class TestAsset extends Asset {}

class AssetTest extends CakeTestCase {
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
		$this->Asset = new TestAsset('css/default.css', $this->file, $this->path);
		$this->debug = Configure::read('debug');
		Configure::write('debug', 0);
	}

	public function tearDown() {
		Configure::write('debug', $this->debug);
	}

	public function testConstruct() {
		$this->assertEquals($this->file, $this->Asset->file);
	}

	public function testDigest() {
		$this->assertEquals('42dcbd72dd658306b48c1161ae1643fc', $this->Asset->digest());
	}

	public function testDigestBundle() {
		$this->Asset = TestAsset::fromUrl('css/bundle.css', $this->path);
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
		$result = TestAsset::fromUrl('css/bundle.css', $this->path);
		$this->assertInstanceOf('Asset', $result);
		$this->assertEquals($this->path . 'css' . DS . 'bundle.css', $result->file);
	}

/**
 * @expectedException InvalidArgumentException
 */
	public function testFromUrlError() {
		TestAsset::fromUrl('invalid.css');
	}

	public function testSize() {
		$this->assertEquals(26, $this->Asset->size());
	}

	public function testContentBundle() {
		$this->Asset = TestAsset::fromUrl('css/bundle.css', $this->path);
		$expected = <<<EOT
body { /* default.css */ }
body { /* bundle.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentDepth() {
		$this->Asset = TestAsset::fromUrl('css/app/bundle.css', $this->path);
		$expected = <<<EOT
body { /* default.css */ }
body { /* bundle.css */ }
body { /* app/bundle.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentCircular() {
		$this->Asset = TestAsset::fromUrl('css/circular.css', $this->path);
		$expected = <<<EOT

body { /* circle.css */ }
body { /* circular.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentRecursive() {
		$this->Asset = TestAsset::fromUrl('css/recursive.css', $this->path);
		$expected = <<<EOT

body { /* recursive.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentAll() {
		$this->Asset = TestAsset::fromUrl('css/all.css', $this->path);
		$expected = <<<EOT
body { /* default.css */ }
body { /* bundle.css */ }
body { /* app/bundle.css */ }


body { /* circular.css */ }
body { /* circle.css */ }



body { /* recursive.css */ }

EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentIncludeTheme() {
		$this->Asset = TestAsset::fromUrl('css/theme.css', $this->path);
		$expected = <<<EOT
body { /* theme/admin/css/bundle.css */ }
body { /* theme.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentIncludePlugin() {
		$this->Asset = TestAsset::fromUrl('css/plugin.css', $this->path);
		$expected = <<<EOT
body { /* other/css/bundle.css */ }
body { /* plugin.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}
}