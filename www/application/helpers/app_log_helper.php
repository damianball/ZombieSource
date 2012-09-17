<?php
	require_once(APPPATH . 'libraries/logging/AppLogFile.php');

	function doAppLog($level, $message) {
		$bt = debug_backtrace();
		$caller = $bt[1]; // two traces ago
		$tmp = explode("/", $caller['file']);
		$file_name = $tmp[count($tmp)-1];

		$date = gmdate("Y-m-d H:i:s");
		$log_string = $level . " - " . $date . " - " . $file_name . " : " . $caller['line'] . " - " . $message;
		AppLogFile::write($log_string);
	}

	function debug($message) {
		doAppLog('DEBUG', $message);
	}

	function info($message) {
		doAppLog('INFO', $message);
	}

	function error($message) {
		doAppLog('ERROR', $message);
	}
?>