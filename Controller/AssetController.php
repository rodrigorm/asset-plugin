<?php 
App::uses('AssetDispatcher', 'Asset.Lib');

class AssetController extends Controller {
	public $autoRender = false;
	public $uses = false;
	public $components = false;
	public $helper = false;
	public $layout = '';

	public function dispatch() {
		$url = implode('/', $this->request->params['pass']);
		$Dispatcher = new AssetDispatcher();
		$Dispatcher->dispatch($url, $this->response);
	}
}