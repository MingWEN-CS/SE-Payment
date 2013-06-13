<?php
import("@.Util.Goods.HotelSuit");

class HotelRoomModel extends GeneralGoodsModel{
	static public function getDataName() {
		return 'hotel_room';
	}
	
	static public function getSourcePlaceObjectsArray() {
		$contentArray = SourcePlace::getSourcePlaceObjectsArray();
		$array = array_merge(array(new SourcePlace("hotel place", "anyplace")), $contentArray);
		return $array;
	}
	
	static protected function generateCondition($purchaseAction) {
		$condition = parent::generateCondition($purchaseAction);
		if(($suit = $purchaseAction->_get('suit_type')) && $suit != "anytype") {
			$condition['suit_type'] = $suit;
		}
		return $condition;
	}
	
	static public function getHotelRoomSuitArray() {
		$contentArray = HotelSuit::getHotelSuitArray();
		$array = array_merge(array(new HotelSuit("suit type", "anytype")),  $contentArray);
		return $array;
	}
}
?>
