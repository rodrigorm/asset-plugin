<?php 
App::uses('Asset', 'Asset.Lib');

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

	public function testFromAsset() {
		$Asset = new Asset('css/default.css', $this->file, $this->path);
		$result = Asset::fromAsset($Asset, 'bundle');
		$this->assertInstanceOf('Asset', $result);
		$this->assertEquals('css/bundle.css', $result->url);
		$this->assertEquals($this->path . 'css' . DS . 'bundle.css', $result->file);

		$Asset = new Asset('css/app/bundle.css', $this->file, $this->path);
		$result = Asset::fromAsset($Asset, '../bundle');
		$this->assertInstanceOf('Asset', $result);
		$this->assertEquals('css/bundle.css', $result->url);
		$this->assertEquals($this->path . 'css' . DS . 'bundle.css', $result->file);

		$Asset = new Asset('css/default.css', $this->file, $this->path);
		$result = Asset::fromAsset($Asset, 'app/bundle');
		$this->assertInstanceOf('Asset', $result);
		$this->assertEquals('css/app/bundle.css', $result->url);
		$this->assertEquals($this->path . 'css' . DS . 'app' . DS . 'bundle.css', $result->file);

		$result = Asset::fromAsset($Asset, '/theme/admin/css/bundle');
		$this->assertInstanceOf('Asset', $result);
		$this->assertEquals('theme/admin/css/bundle.css', $result->url);
		$this->assertEquals(App::themePath('admin') . 'webroot' . DS . 'css' . DS . 'bundle.css', $result->file);
	}

	public function testSize() {
		$this->assertEquals(26, $this->Asset->size());
	}

	public function testContentBundle() {
		$this->Asset = Asset::fromUrl('css/bundle.css', $this->path);
		$expected = <<<EOT
body { /* default.css */ }
body { /* bundle.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentDepth() {
		$this->Asset = Asset::fromUrl('css/app/bundle.css', $this->path);
		$expected = <<<EOT
body { /* default.css */ }
body { /* bundle.css */ }
body { /* app/bundle.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentCircular() {
		$this->Asset = Asset::fromUrl('css/circular.css', $this->path);
		$expected = <<<EOT

body { /* circle.css */ }
body { /* circular.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentRecursive() {
		$this->Asset = Asset::fromUrl('css/recursive.css', $this->path);
		$expected = <<<EOT

body { /* recursive.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentAll() {
		$this->Asset = Asset::fromUrl('css/all.css', $this->path);
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
		$this->Asset = Asset::fromUrl('css/theme.css', $this->path);
		$expected = <<<EOT
body { /* theme/admin/css/bundle.css */ }
body { /* theme.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentIncludePlugin() {
		$this->Asset = Asset::fromUrl('css/plugin.css', $this->path);
		$expected = <<<EOT
body { /* other/css/bundle.css */ }
body { /* plugin.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}
}