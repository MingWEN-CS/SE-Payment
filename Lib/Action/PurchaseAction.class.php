<?php
import("@.Util.Goods.SourcePlace");
import("@.Util.Goods.GoodsHelper");

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
		$uid = $this->_session('uid');
		$uname = $this->session('username');
		//Show shopping list
		$shopping_cart_list = $this->_post();
		$commodity_list = $shopping_cart_list['good_pairs'];
		$list_count = count($commodity_list) / 2;
		$total_price = 0;
		for($i = 0; $i < $list_count; $i++) {
			$goods_id = $commodity_list[2*$i]['good_id'];
			$goods_info = GoodsHelper::getBasicGoodsInfoOfId($goods_id);
			$goods_info['count'] = $commodity_list[2*$i+1]['good_count'];
			$goods_info_list[$i] = $goods_info;
			$total_price = $total_price + $goods_info['price'] * $goods_info['count'];
		}
		$this->assign('goods_info_list', $goods_info_list);
		$this->assign('total_price', $total_price);
		//var_dump($goods_info_list);
		
		//Show and select shipping address
		

		$this->display();
	}

}
?>
