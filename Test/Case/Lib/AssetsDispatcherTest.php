<?php 
App::uses('AssetsDispatcher', 'Asset.Lib');
App::uses('CakeResponse', 'Network');

class AssetsDispatcherTest extends CakeTestCase {
	public function setUp() {
		$this->env = AssetEnvironment::getInstance(App::pluginPath('Asset') . 'Test' . DS . 'test_app' . DS . 'webroot' . DS);
		$this->Dispatcher = new AssetsDispatcher($this->env);
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