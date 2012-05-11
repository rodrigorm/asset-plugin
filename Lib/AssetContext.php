<?php 
App::uses('AssetEnvironment', 'Asset.Lib');
App::uses('AssetFactory', 'Asset.Lib');

class AssetContext {
	protected $_env;
	protected $_dependencies = array();

	public function __construct($env = null) {
		$this->_env = AssetEnvironment::getInstance($env);
	}

	public function resolve($asset, Asset $relative = null) {
		if ($relative) {
			return AssetFactory::fromAsset($relative, $asset);
		}
		return AssetFactory::fromUrl($asset, $this->_env);
	}

	public function load($asset, Asset $relative = null) {
		$result = $this->resolve($asset, $relative);
		if ($this->depend($result)) {
			return new NullAsset($result->url, $result->file, $result->env);
		}
		return $result;
	}

	public function depend(Asset $asset) {
		if (isset($this->_dependencies[$asset->file])) {
			return true;
		}
		$this->_dependencies[$asset->file] = true;
		return false;
	}
}