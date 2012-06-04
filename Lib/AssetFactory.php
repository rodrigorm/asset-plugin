<?php 
App::uses('Asset', 'Asset.Lib');
App::uses('CssAsset', 'Asset.Lib');
App::uses('JsAsset', 'Asset.Lib');
App::uses('StaticAsset', 'Asset.Lib');

class AssetFactory {
	public function fromUrl($url, $env = null) {
		$info = pathinfo($url);
		if ($info['extension'] == 'css') {
			return CssAsset::fromUrl($url, $env);
		}
		if ($info['extension'] == 'js') {
			return JsAsset::fromUrl($url, $env);
		}

		return StaticAsset::fromUrl($url, $env);
	}

	static public function fromAsset($relative, $url) {
		$info = pathinfo($url);
		if (empty($info['extension'])) {
			$info['extension'] = $relative->extension();
		}
		$info['dirname'] .= '/';

		$asset = $info['dirname'] . $info['filename'] . '.' . $info['extension'];
		if (substr($asset, 0, 1) !== '/') {
			$asset = $relative->dirname() . '/' . $asset;
		}
		$asset = preg_replace('/\w+\/\.\.\//', '', $asset);
		$asset = str_replace('./', '', $asset);
		$asset = preg_replace('#\/{2,}#', '/', $asset);
		$asset = preg_replace('#^\/#', '', $asset);
		return self::fromUrl($asset, $relative->env);
	}
}