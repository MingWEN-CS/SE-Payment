<?php
import("@.Util.Goods.FlightCarbin");

class AirplaneTicketModel extends GeneralGoodsModel{
	static public function getDataName() {
		return 'airplane_ticket';
	}
	
	static public function getSourcePlaceObjectsArray() {
		$arrayContent = SourcePlace::getSourcePlaceObjectsArray();
		$arrayContent = array_merge(array(new SourcePlace("departure place", "anyplace")), $arrayContent);
		return $arrayContent;
	}
	
	static public function getArrivalPlaceObjectsArray() {
		$arrayContent = SourcePlace::getSourcePlaceObjectsArray();
		$arrayContent = array_merge(array(new SourcePlace("arrival place", "anyplace")), $arrayContent);
		return $arrayContent;
	}
	
	static public function getAirplaneTicketCarbinArray() {
		$contentArray = FlightCarbin::getFlightCarbinArray();
		$array = array_merge(array(new FlightCarbin("carbin type", "anytype")),  $contentArray);
		return $array;
	}
	
	static protected function generateCondition($purchaseAction) {
		$condition = parent::generateCondition($purchaseAction);
		if(($place = $purchaseAction->_get('departure_place')) && $place != "anyplace") {
			$condition['departure_place'] = $place;
		}
		if(($place = $purchaseAction->_get('arrival_place')) && $place != "anyplace") {
			$condition['arrival_place'] = $place;
		}
		if(($carbin = $purchaseAction->_get('carbin_type')) && $carbin != "anytype") {
			$condition['carbin_type'] = $carbin;
		}
		if(($departurePlace = $purchaseAction->_get('departure_date_time'))) {
			$startTime = strtotime($departurePlace);
			$condition['departure_date_time'] = array('between',array($startTime, $startTime + 60 * 5));
		}
		if(($arrivalPlace = $purchaseAction->_get('arrival_date_time'))) {
			$startTime = strtotime($arrivalPlace);
			$condition['arrival_date_time'] = array('between',array($startTime, $startTime + 60 * 5));
		}
		return $condition;
	}
	
	static public function getType() {
		return "AirplaneTicketModel";
	}
}
?>
