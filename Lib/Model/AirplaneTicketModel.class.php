<?php
import("@.Util.Goods.FlightCarbin");

class AirplaneTicketModel extends GeneralGoodsModel{
	protected $_validate = array(
		array('name', 'require', "Good's name is necessary!"),
		array('price', 'require', 'Price is necessary!'),
		array('price', 'currency', 'Price is not valid!'),
		array('stock', 'require', 'Stock is necessary!'),
		array('stock', 'number', 'Stock must be a number!'),
		array('departure_date_time', 'require', 'Departure time is necessary!'),
		array('arrival_date_time', 'require', 'Arrival time is necessary!'),
		array('departure_place', 'require', 'Departure place is necessary!'),
		array('arrival_place', 'require', 'Arrival place is necessary!'),
		array('non_stop', 'require', 'You should select whether the plane need transfer!'),
	);
	
	protected $_auto = array(
		array('bought_count', '0'),
		array('score', '0'),
		array('score_count', '0'),
	);

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
