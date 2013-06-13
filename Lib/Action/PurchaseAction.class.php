<?php
import("@.Util.Goods.SourcePlace");

class PurchaseAction extends Action {
	
    public function index(){
		$this->display();
    }

    public function login(){
    	$this->display("index");
    }

    public function search(){
		$goods_type = $this->_get('goods-type');
		$keywords = $this->_get('keywords');
		if($goods_type == 'general-goods') {
			$goods = D('GeneralGoods');
		}
		else if($goods_type == 'airplane-ticket') {
			$goods = D('AirplaneTicket');
		}
		else if($goods_type == 'hotel-room') {
			$goods = D('HotelRoom');
		}
		$searchResult = $goods->getGoodsWithPurchaseAction($this);
		$this->assign($goods->getDataName(), $searchResult);
		$this->assign('keywords', $keywords);
		//3 kinds goods' sort option
		$this->assign('general_goods_sort_options', GeneralGoodsModel::getSortFieldArray());
		$this->assign('hotel_room_sort_options', AirplaneTicketModel::getSortFieldArray());
		$this->assign('airplane_ticket_sort_options', HotelRoomModel::getSortFieldArray());
		//3 kinds goods' source place option
		$this->assign('general_goods_source_place', GeneralGoodsModel::getSourcePlaceObjectsArray());
		$this->assign('hotel_room_source_place', HotelRoomModel::getSourcePlaceObjectsArray());
		$this->assign('airplane_ticket_departure_place', AirplaneTicketModel::getSourcePlaceObjectsArray());
		$this->assign('airplane_ticket_arrival_place', AirplaneTicketModel::getArrivalPlaceObjectsArray());
		//hotel suit
		$this->assign('hotel_room_suit', HotelRoomModel::getHotelRoomSuitArray());
		//airplane carbin
		$this->assign('airpalne_ticket_carbin', AirplaneTicketModel::getAirplaneTicketCarbinArray());
		$this->display();
	}
	
	public function ordergen() {
		$commodity_list = $this->_post('good_pairs');
		echo($commodity_list);
		$this->display();
	}
	
}
?>
