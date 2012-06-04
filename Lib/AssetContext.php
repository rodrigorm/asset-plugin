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

	public function depend($asset, Asset $relative = null) {
		$result = $this->resolve($asset, $relative);
		if ($this->dependent($result)) {
			return new NullAsset($result->url, $result->file, $result->env);
		}
		$this->depends($result);
		return $result;
	}

	public function dependent(Asset $asset) {
		return isset($this->_dependencies[$asset->file]);
	}

	public function depends(Asset $asset) {
		$this->_dependencies[$asset->file] = true;
	}
}