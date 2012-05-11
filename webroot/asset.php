<?php 
App::uses('AssetDispatcher', 'Asset.Lib');
$Dispatcher = new AssetDispatcher();
$Dispatcher->dispatch(preg_replace('#^(.+)\.([\da-f]+)\.(\w+)$#', '$1.$3', $url), $response);