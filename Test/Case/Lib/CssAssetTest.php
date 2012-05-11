<?php 
App::uses('CssAsset', 'Asset.Lib');

class CssAssetTest extends CakeTestCase {
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
		$this->Asset = new CssAsset('css/default.css', $this->file, $this->path);
	}

	public function testContent() {
		$expected = 'body { /* default.css */ }';
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentBundle() {
		$this->Asset = CssAsset::fromUrl('css/bundle.css', $this->path);
		$expected = <<<EOT
@import "/css/default-42dcbd72dd658306b48c1161ae1643fc.css";
body { /* bundle.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentDepth() {
		$this->Asset = CssAsset::fromUrl('css/app/bundle.css', $this->path);
		$expected = <<<EOT
@import "/css/bundle-21f0b94760f11e0f77c6548534474bd6.css";
body { /* app/bundle.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentCircular() {
		$this->Asset = CssAsset::fromUrl('css/circular.css', $this->path);
		$expected = <<<EOT
@import "/css/circle-9154866ae9356bf7b31519dffc0ce8cd.css";
body { /* circular.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentRecursive() {
		$this->Asset = CssAsset::fromUrl('css/recursive.css', $this->path);
		$expected = <<<EOT

body { /* recursive.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentAll() {
		$this->Asset = CssAsset::fromUrl('css/all.css', $this->path);
		$expected = <<<EOT
@import "/css/app/bundle-fb0ec2301db03eca534d8b3b6f4634df.css";
@import "/css/bundle-21f0b94760f11e0f77c6548534474bd6.css";
@import "/css/circle-9154866ae9356bf7b31519dffc0ce8cd.css";
@import "/css/circular-af897e1b45ec95aaaee1718c448d4258.css";
@import "/css/default-42dcbd72dd658306b48c1161ae1643fc.css";
@import "/css/recursive-b81628311c980742f36af8828d46962d.css";

EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentIncludeTheme() {
		$this->Asset = CssAsset::fromUrl('css/theme.css', $this->path);
		$expected = <<<EOT
@import "/theme/admin/css/bundle-31bbb3bb5d6a25b66edd3500494b95a2.css";
body { /* theme.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentIncludePlugin() {
		$this->Asset = CssAsset::fromUrl('css/plugin.css', $this->path);
		$expected = <<<EOT
@import "/other/css/bundle-910318eb7938fd444cf06516ce70b8d1.css";
body { /* plugin.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}
}