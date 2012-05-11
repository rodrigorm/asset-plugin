<?php 
App::uses('AssetTimestamper', 'Asset.Lib');

class AssetTimestamperTest extends CakeTestCase {
	public function setUp() {
		$this->filePath = App::pluginPath('Asset') . 'Test' . DS . 'files' . DS;
	}

	public function testTimestamp() {
		$result = AssetTimestamper::timestamp('photo.jpg', $this->filePath . 'photo.jpg');
		$this->assertEquals('photo.d41d8cd98f00b204e9800998ecf8427e.jpg', $result);
	}

	public function testTimestampNotExists() {
		$result = AssetTimestamper::timestamp('invalid.jpg', $this->filePath . 'invalid.jpg');
		$this->assertEquals('invalid.jpg', $result);
	}
}