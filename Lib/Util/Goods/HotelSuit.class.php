<?php
class HotelSuit {
	static public function getHotelSuitArray(){
		return array(
			new HotelSuit('单人间', 'single'),
			new HotelSuit('标准间', 'standard'),
			new HotelSuit('豪华间', 'luxury'),
			new HotelSuit('商务间', 'bussiness'),
			new HotelSuit('高级间', 'advanced')
		);
	}
	
	var $display;
	var $value;
	function HotelSuit($display, $value) {
		$this->display = $display;
		$this->value = $value;
	}
}
?>
