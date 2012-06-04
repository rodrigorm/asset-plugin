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
@import "/asset/css/default-42dcbd72dd658306b48c1161ae1643fc.css";
body { /* bundle.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentDepth() {
		$this->Asset = CssAsset::fromUrl('css/app/bundle.css', $this->path);
		$expected = <<<EOT
@import "/asset/css/bundle-f9dfe48ba73e9a71e845add7a7d84fd3.css";
body { /* app/bundle.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentCircular() {
		$this->Asset = CssAsset::fromUrl('css/circular.css', $this->path);
		$expected = <<<EOT
@import "/asset/css/circle-770ed09c508d3d2b86a18b8a1ec2d2aa.css";
body { /* circular.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentRecursive() {
		$this->Asset = CssAsset::fromUrl('css/recursive.css', $this->path);
		$expected = <<<EOT
@import "/asset/css/recursive-d41d8cd98f00b204e9800998ecf8427e.css";
body { /* recursive.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentAll() {
		$this->Asset = CssAsset::fromUrl('css/all.css', $this->path);
		$expected = <<<EOT
@import "/asset/css/app/bundle-fdffc148046efffe279fc79e73a596f6.css";
@import "/asset/css/bundle-d41d8cd98f00b204e9800998ecf8427e.css";
@import "/asset/css/circle-c823e0be72a0338ef68cbe15374899d3.css";
@import "/asset/css/circular-d41d8cd98f00b204e9800998ecf8427e.css";
@import "/asset/css/default-d41d8cd98f00b204e9800998ecf8427e.css";
@import "/asset/css/recursive-9ae1321ab57dda9a510774ae1f80310a.css";
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentIncludeTheme() {
		$this->Asset = CssAsset::fromUrl('css/theme.css', $this->path);
		$expected = <<<EOT
@import "/asset/theme/admin/css/bundle-31bbb3bb5d6a25b66edd3500494b95a2.css";
body { /* theme.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}

	public function testContentIncludePlugin() {
		$this->Asset = CssAsset::fromUrl('css/plugin.css', $this->path);
		$expected = <<<EOT
@import "/asset/other/css/bundle-910318eb7938fd444cf06516ce70b8d1.css";
body { /* plugin.css */ }
EOT;
		$this->assertEquals($expected, $this->Asset->content());
	}
}