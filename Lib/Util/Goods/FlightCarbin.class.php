<?php
class FlightCarbin {
	static public function getFlightCarbinArray(){
		return array(
			new HotelSuit('头等舱', 'first'),
			new HotelSuit('经济舱', 'economy'),
			new HotelSuit('商务舱', 'bussiness')
		);
	}
	
	var $display;
	var $value;
	function FlightCarbin($display, $value) {
		$this->display = $display;
		$this->value = $value;
	}
}
?>
