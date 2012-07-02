<?php 
App::uses('AssetsDispatcher', 'Asset.Lib');

class AssetController extends Controller {
	public $autoRender = false;
	public $uses = false;
	public $components = false;
	public $helper = false;
	public $layout = '';

	public function dispatch() {
		$url = implode('/', $this->request->params['pass']);
		$Dispatcher = new AssetsDispatcher();
		$Dispatcher->dispatch($url, $this->response);
	}
}