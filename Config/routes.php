<?php 
Router::connect('/asset/*', array('plugin' => 'asset', 'controller' => 'asset', 'action' => 'dispatch'));