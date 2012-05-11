<?php 
App::uses('AssetDispatcher', 'Asset.Lib');
App::uses('CakeResponse', 'Network');

class AssetDispatcherTest extends CakeTestCase {
	public function setUp() {
		$this->env = AssetEnvironment::getInstance(App::pluginPath('Asset') . 'Test' . DS . 'test_app' . DS . 'webroot' . DS);
		$this->Dispatcher = new AssetDispatcher($this->env);
	}

	public function testDispatch() {
		$response = $this->getMock('CakeResponse');
		$response->expects($this->once())->method('send');
		$this->Dispatcher->dispatch('css/bundle.css', $response);
	}

	public function testDispatchInvalid() {
		$response = $this->getMock('CakeResponse');
		$response->expects($this->once())->method('statusCode')->with($this->equalTo('404'));
		$response->expects($this->once())->method('send');
		$this->Dispatcher->dispatch('css/invalid.css', $response);
	}
}