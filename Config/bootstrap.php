<?php 
if (!function_exists('assetGetRelativePath')) {
	function assetGetRelativePath($from, $to) {
		$from = explode('/', $from);
		$to = explode('/', $to);
		foreach ($from as $depth => $dir) {
			if (isset($to[$depth])) {
				if ($dir === $to[$depth]) {
					unset($to[$depth]);
					unset($from[$depth]);
				} else {
					break;
				}
			}
		}

		for ($i = 0; $i < count($from) - 1; $i++) {
			array_unshift($to, '..');
		}
		$result = implode('/', $to);
		return $result;
	}
}

$filter = assetGetRelativePath(WWW_ROOT, App::pluginPath('Asset') . 'webroot' . DS) . 'asset.php';
Configure::write('Asset.filter.css', $filter);
Configure::write('Asset.filter.js', $filter);
unset($filter);