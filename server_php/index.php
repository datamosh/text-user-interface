<?php
	// Remember edit this line for better security
	// More info: http://stackoverflow.com/questions/12001269/what-are-the-security-risks-of-setting-access-control-allow-origin
	header('Access-Control-Allow-Origin: *');

	// Config
	$config = json_decode(file_get_contents('../config.json'));

	function getObjects($path) {
		global $global_path;

		// Scandir
		$path .= '/';
		$array = scandir($path);

		// Filter
		$array = array_diff($array, array('.', '..', '.DS_Store', 'Thumbs.db'));

		// Prepare return
		$return_array = array();

		foreach($array as $item) {
			if(is_dir($path . $item))
				$return_array[$item] = getObjects($path . $item);
		}

		$count = 0;
		foreach($array as $item)
			if(!is_dir($path . $item)) {
				$return_array[$item] = [
					'name' => $item,
					'path' => str_replace($global_path, '', $path . $item),
					'extension' => strtolower(pathinfo($path . $item, PATHINFO_EXTENSION)),
					'size' => filesize($path . $item),
					'type' => 'file'
				];
				$count++;
			}
		if($count == 0)
			$return_array[''] = '';

		return $return_array;
	}

	$global_path = '../' . $config->path;
	$files = getObjects($global_path);

	echo json_encode($files, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);

	switch (json_last_error()) {
		case JSON_ERROR_NONE:
			// ..
		break;
		case JSON_ERROR_DEPTH:
			die('Maximum stack depth exceeded');
		break;
		case JSON_ERROR_STATE_MISMATCH:
			die('Underflow or the modes mismatch');
		break;
		case JSON_ERROR_CTRL_CHAR:
			die('Unexpected control character found');
		break;
		case JSON_ERROR_SYNTAX:
			die('Syntax error, malformed JSON');
		break;
		case JSON_ERROR_UTF8:
			die('Malformed UTF-8 characters, possibly incorrectly encoded');
		break;
		default:
			die('Unknown error');
		break;
	}
?>