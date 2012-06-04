<?php 
App::uses('AssetFilter', 'Asset.Lib');

class AssetSprocketsFilter extends AssetFilter implements AssetInputFilter {
	protected $_pattern = '/^\s?\/\/\=\s+require\s+(\"?)([^\"\n]+)\1(\n+|$)/m';

	public function input($content) {
		return $this->_process($content);
	}

	protected function _process($content) {
		return preg_replace_callback(
			$this->_pattern,
			array($this, '_replace'),
			$content
		);
	}

	protected function _replace($matches) {
		return $this->import($matches[2]) . $matches[3];
	}
}