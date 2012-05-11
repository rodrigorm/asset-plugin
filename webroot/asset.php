<?php 
App::uses('AssetDispatcher', 'Asset.Lib');
$Dispatcher = new AssetDispatcher();
$Dispatcher->dispatch($url, $response);