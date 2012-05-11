<?php 
class AssetTimestamper {
	static protected function _assetHash($filename) {
		$result = @md5_file($filename);
		if (empty($result)) {
			$result = @filemtime($filename);
		}
		return $result;
	}

	static public function timestamp($path, $filename) {
		$hash = static::_assetHash($filename);
		if (empty($hash)) {
			return $path;
		}

		$parts = explode('.', $path);
		$extension = array_pop($parts);
		array_push($parts, $hash, $extension);
		return implode('.', $parts);
	}
}