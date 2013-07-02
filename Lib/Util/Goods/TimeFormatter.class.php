<?php	
class TimeFormatter {
	static function formatTime($timestamp) {
		return date("Y M d H:i", $timestamp);
	}
}
?>