<?php
import("@.Util.Goods.SourcePlace");
import("@.Util.Goods.GoodsHelper");
import("@.Util.Goods.TimeFormatter");
import("@.Util.User.BuyerHelper");
import("@.Util.CommonValue");

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
		for($i = 0; $i < count($searchResult);$i++) {
			$searchResult[$i][image_uri] = CommonValue::getImgUploadPath() . $searchResult[$i][image_uri];
			$searchResult[$i][vip_price] = $searchResult[$i][price] * CommonValue::getVipDiscount();
		}
		$this->assign($goods->getDataName(), $searchResult);
		$this->assign('keywords', $keywords);
		$this->assign('is_vip', BuyerHelper::getIsVip($userId));
		//3 kinds goods' sort option
		$this->assign('general_goods_sort_options', GeneralGoodsModel::getSortFieldArrayWithHead());
		$this->assign('hotel_room_sort_options', AirplaneTicketModel::getSortFieldArrayWithHead());
		$this->assign('airplane_ticket_sort_options', HotelRoomModel::getSortFieldArrayWithHead());
		//3 kinds goods' source place option
		$this->assign('general_goods_source_place', GeneralGoodsModel::getSourcePlaceObjectsArrayWithHead());
		$this->assign('hotel_room_source_place', HotelRoomModel::getSourcePlaceObjectsArrayWithHead());
		$this->assign('airplane_ticket_departure_place', AirplaneTicketModel::getSourcePlaceObjectsArrayWithHead());
		$this->assign('airplane_ticket_arrival_place', AirplaneTicketModel::getArrivalPlaceObjectsArrayWithHead());
		//hotel suit
		$this->assign('hotel_room_suit', HotelRoomModel::getHotelRoomSuitArrayWithHead());
		//airplane carbin
		$this->assign('airpalne_ticket_carbin', AirplaneTicketModel::getAirplaneTicketCarbinArrayWithHead());
		//hotest goods
		$hotest = GoodsHelper::getHotestGoods(10);
		for($i = 0; $i < count($hotest);$i++) {
			$hotest[$i][image_uri] = CommonValue::getImgUploadPath() . $hotest[$i][image_uri];
		}
		$this->assign('hotest_goods', $hotest);
		$this->display();
	}
	
	public function detail() {
		$id = $this->_get('id');
		if(!$id) {
			$this->error();
			return;
		}
		$good = GoodsHelper::getBasicGoodsInfoOfId($id);
		$type = GoodsHelper::getGoodsTypeOfId($id);
		if(!$good) {
			$this->error();
			return;
		}
		$userId = $this->_session('uid');
		if (IS_POST) {
			$buyer = M('Buyer');
			if (!$userId || !$buyer->where('uid = '.$userId)->find()) {
		    	$this->ajaxReturn(0, 'To use shopping cart, you must login as a buyer!'.$userId, 0);
				return;
			}
			else {
				$shoppingCart = D('ShoppingCart');
				$shoppingCartRecord = $shoppingCart->where('user_id = '.$userId.' AND good_id = '.$id)->find();
				//exists already
				if($shoppingCartRecord) {
					$shoppingCart->modifyCount($userId, $id, 'true');
		            $this->ajaxReturn(0, "Add succeeded", 1);
					return;
				}
				//no this goods in shopping cart
				else {
					$data = array(
						'good_id' => $id,
						'user_id' => $userId,
						'good_count' => 1,
					);
					if ($shoppingCart->create($data)){
						$id = $shoppingCart->add();
						if($id) {
				            $this->ajaxReturn(0, "Add succeeded", 1);
							return;
						}
					}
				}
				$this->ajaxReturn(0, "Add failed.", 0);
	        }
		}
		else {
			if($userId) {
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
			if($type == HotelRoomModel::getType()) {
				$good[date_time] = TimeFormatter::formatTime($good[date_time]);
			}
			else if($type == AirplaneTicketModel::getType()) {
				$good[departure_date_time] = TimeFormatter::formatTime($good[departure_date_time]);
				$good[arrival_date_time] = TimeFormatter::formatTime($good[arrival_date_time]);
			}
			$this->assign('feedbacks', $feedbacksFull);
			$good[score] = intval($good[score]);
			$good[image_uri] = CommonValue::getImgUploadPath() . $good[image_uri];
			$good[vip_price] = $good[price] * CommonValue::getVipDiscount();
			$this->assign('is_vip', BuyerHelper::getIsVip($userId));
			$this->assign('goods_info', $good);
			$this->assign('goods_type', $type);
			$this->assign('general_goods_type', GeneralGoodsModel::getType());
			$this->assign('hotel_room_type', HotelRoomModel::getType());
			$this->assign('airplane_ticket_type', AirplaneTicketModel::getType());
			$this->display();
		}
	}
	
	public function ordergen() {
		//Session info
		$uid = $this->_session('uid');
		$uname = $this->_session('username');
		$is_vip = BuyerHelper::getIsVip($uid);
		
		$User = D('Buyer');
		if(!$uid || !$User->where('UID='.$uid)->select()) {
			$this->error('Please login as a buyer first!','__APP__/User/login');
		}
		
		//Show shopping list
		$Order = D('Orders');
		$Order_goods = D('Order_goods');
		$shopping_cart_list = $this->_post();
		$commodity_list = $shopping_cart_list['good_pairs'];
		$list_count = count($commodity_list) / 2;
		$total_price = 0;
		for($i = 0; $i < $list_count; $i++) {
			$goods_id = $commodity_list[2*$i]['good_id'];
			$goods_info = GoodsHelper::getBasicGoodsInfoOfId($goods_id);
			//vip discount
			if($is_vip) {
				$goods_info['price'] = $goods_info['price'] * CommonValue::getVipDiscount();
			}
			$goods_info['count'] = $commodity_list[2*$i+1]['good_count'];
			$goods_info_list[$i] = $goods_info;
			$goods_list_int[$i]['goods_id'] = $goods_id;
			$goods_list_int[$i]['goods_count'] = $goods_info['count'];
			$total_price = $total_price + $goods_info['price'] * $goods_info['count'];
		}

		//Generate imcomplete order and get order_id list (int group 2)
		$order_list = R('Order/createorder',array($goods_list_int));
		$order_count = count($order_list);
		//var_dump($order_list);
			
		$this->assign('order_list', $order_list);
		$this->assign('order_count', $order_count);
		$this->assign('goods_info_list', $goods_info_list);
		$this->assign('total_price', $total_price);
		
		//Show and select shipping address
		$addr = D('Receiveaddress');
		$condition['UID'] = $uid;
		$addr_list = $addr->where($condition)->select();
		//var_dump($addr_list);

		
		$this->assign('addr_list', $addr_list);
		$this->display();
	}

	public function orderprocess() {
		//Session info
		$uid = $this->_session('uid');
		$uname = $this->_session('username');
		
		$User = D('Buyer');
		if(!$uid || !$User->where('UID='.$uid)->select()) {
			$this->error('Please login as a buyer first!','__APP__/User/login');
		}

		$order_info = $this->_post();
		$Order = D('Orders');

		$order_count = $order_info['order_count'];
		
		//generate order
		if (isset($order_info['generate'])) {
			for($i = 1; $i <= $order_count; $i++) {
				$order_id = $order_info['order_id_'.$i];
				$data['ADDRESSID'] = $order_info['addr_sel'];
				$result = $Order->where('ID='.$order_id)->save($data);
			}
		
			$this->success('Your Order is Generated Successfully!', '__APP__/Order/showorders');
		}
		//cancel order
		else {
			$this->success('Your Order is canceled', '__APP__');
		}
	}
}
?>
