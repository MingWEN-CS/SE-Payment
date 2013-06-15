<?php
import("@.Util.Goods.SourcePlace");
import("@.Util.Goods.GoodsHelper");
import("@.Util.Goods.TimeFormatter");

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
		if($userId = $this->_session('uid')) {
			$searchHistory = D('SearchHistory');
            $data = array(
                'search_key' => $keywords,
                'user_id' => $userId,
                'date_time' => time(),
            );
            if ($searchHistory->create($data)){
                $id = $searchHistory->add();
			}
		}
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
		if($goods->getType() == HotelRoomModel::getType()) {
			for($i = 0; $i < count($searchResult);$i++) {
				$searchResult[$i][date_time] = TimeFormatter::formatTime($searchResult[$i][date_time]);
			}
		}
		else if($goods->getType() == AirplaneTicketModel::getType()) {
			for($i = 0; $i < count($searchResult);$i++) {
				$searchResult[$i][departure_date_time] = TimeFormatter::formatTime($searchResult[$i][departure_date_time]);
				$searchResult[$i][arrival_date_time] = TimeFormatter::formatTime($searchResult[$i][arrival_date_time]);
			}
		}
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
	
	public function detail() {
		$id = $this->_get('id');
		if(!$id) {
			$this->display();
			return;
		}
		$good = GoodsHelper::getBasicGoodsInfoOfId($id);
		$type = GoodsHelper::getGoodsTypeOfId($id);
		if(!$good) {
			$good->display();
			return;
		}
		if($userId = $this->_session('uid')) {
			$browseHistory = D('BrowseHistory');
            $data = array(
                'good_id' => $id,
                'user_id' => $userId,
                'date_time' => time(),
            );
            if ($browseHistory->create($data)){
                $id = $browseHistory->add();
			}
		}
		$feedback = D('Feedback');
		$user = D('User');
		$feedbacks = $feedback->where('goods_id=' . $id)->select();
		$feedbacksFull = array();
		foreach($feedbacks as $eachFeedback) {
			$userInfo = $user->where('UID=' . $eachFeedback[user_id])->find();
			$newFeedback = array_merge($eachFeedback, array(username => $userInfo[USERNAME]));
			$feedbacksFull = array_merge($feedbacksFull, array($newFeedback));
		}
		$this->assign('feedbacks', $feedbacksFull);
		$good[score] = intval($good[score]);
		$this->assign('goods_info', $good);
		$template = "";
		if($type  == GeneralGoodsModel::getType()) {
		}
		else if($type == HotelRoomModel::getType()) {
			$template = 'detail_hotel_room';
		}
		else if($type == AirplaneTicketModel::getType()) {
			$template = 'detail_airplane_ticket';
		}
		$this->display($template);
	}
	
	public function ordergen() {
		$commodity_list = $this->_post('good_pairs');
		echo($commodity_list);
		$this->display();
	}
}
?>