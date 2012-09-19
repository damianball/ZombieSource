<?php

class GameTime {
	
	public static function gmdate($formatString = null, $unixTimestamp = null) {
		if ($formatString == null) {
			$formatString = "Y-m-d H:i:s";
		}

		if ($unixTimestamp == null) {
			$unixTimestamp = GameTime::time();
		}

		return gmdate($formatString, $unixTimestamp);
	}

	public static function time() {
		return time();
	}
}