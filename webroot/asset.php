<?php 
App::uses('AssetsDispatcher', 'Asset.Lib');
$Dispatcher = new AssetsDispatcher();
$Dispatcher->dispatch($url, $response);