<?php	
class TimeFormatter {
	static function formatTime($timestamp) {
		return gmdate("Y M d H:i", $timestamp);
	}
}
?>