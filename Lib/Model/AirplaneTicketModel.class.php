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
		return SourcePlace::getSourcePlaceObjectsArray();
	}
	
	static public function getArrivalPlaceObjectsArray() {
		return SourcePlace::getSourcePlaceObjectsArray();
	}
	
	static public function getAirplaneTicketCarbinArray() {
		return FlightCarbin::getFlightCarbinArray();
	}
	
	static public function getSourcePlaceObjectsArrayWithHead() {
		$arrayContent = SourcePlace::getSourcePlaceObjectsArray();
		$arrayContent = array_merge(array(new SourcePlace("departure place", "anyplace")), $arrayContent);
		return $arrayContent;
	}
	
	static public function getArrivalPlaceObjectsArrayWithHead() {
		$arrayContent = SourcePlace::getSourcePlaceObjectsArray();
		$arrayContent = array_merge(array(new SourcePlace("arrival place", "anyplace")), $arrayContent);
		return $arrayContent;
	}
	
	static public function getAirplaneTicketCarbinArrayWithHead() {
		$contentArray = FlightCarbin::getFlightCarbinArray();
		$array = array_merge(array(new FlightCarbin("carbin type", "anytype")),  $contentArray);
		return $array;
	}
	
	static protected function generateCondition($purchaseAction) {
		$condition = parent::generateCondition($purchaseAction);
		//where cause of departure_place
		if(($place = $purchaseAction->_get('departure_place')) && $place != "anyplace") {
			$condition['departure_place'] = $place;
		}
		//where cause of arrival_place
		if(($place = $purchaseAction->_get('arrival_place')) && $place != "anyplace") {
			$condition['arrival_place'] = $place;
		}
		//where cause of departure_place
		if(($carbin = $purchaseAction->_get('carbin_type')) && $carbin != "anytype") {
			$condition['carbin_type'] = $carbin;
		}
		//where cause of departure_time
		if(($departureTime = $purchaseAction->_get('departure_date_time'))) {
			$startTime = strtotime($departureTime. " +0000");
			$condition['departure_date_time'] = array('between',array($startTime -60, $startTime + 60 * 5));
		}
		//where cause of arrival_time
		if(($arrivalTime = $purchaseAction->_get('arrival_date_time'))) {
			$startTime = strtotime($arrivalTime. " +0000");
			$condition['arrival_date_time'] = array('between',array($startTime- 60, $startTime + 60 * 5));
		}
		//where cause of non_stop
		if((($nonStop = $purchaseAction->_get('non_stop'))&&$nonStop!=-1)||$nonStop==0) {
			$condition['non_stop'] = $nonStop;
		}
		return $condition;
	}
	
	static public function getType() {
		return "AirplaneTicketModel";
	}
}
?>
