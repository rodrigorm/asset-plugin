<?php 
App::uses('AssetSprocketsFilter', 'Asset.Filter');
App::uses('AssetContext', 'Asset.Lib');
App::uses('AssetProcessor', 'Asset.Lib');
App::uses('Asset', 'Asset.Lib');

class AssetSprocketsFilterTest extends CakeTestCase {
	public function setUp() {
		$this->Asset = $this->getMock('Asset', array(), array('asset', '/file'));
		$this->Context = $this->getMock('AssetContext');
		$this->Processor = new AssetProcessor($this->Asset, $this->Context);
		$this->Filter = new AssetSprocketsFilter($this->Processor);
	}

	public function testInput() {
		$asset = $this->getMock('Asset', array(), array('asset', '/file'));
		$this->Context->expects($this->once())
			->method('depend')
			->with($this->equalTo('asset'))
			->will($this->returnValue($asset));
		$asset->expects($this->once())
			->method('import')
			->will($this->returnValue('asset content'));

		$content = '//= require asset';
		$this->assertEquals("asset content", $this->Filter->input($content));
	}

	public function testInputNewLine() {
		$asset = $this->getMock('Asset', array(), array('asset', '/file'));
		$this->Context->expects($this->once())
			->method('depend')
			->with($this->equalTo('asset'))
			->will($this->returnValue($asset));
		$asset->expects($this->once())
			->method('import')
			->will($this->returnValue('asset content'));

		$content = "//= require asset\n";
		$this->assertEquals("asset content\n", $this->Filter->input($content));
	}
}