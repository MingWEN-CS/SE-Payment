<?php
// 本类由系统自动生成，仅供测试用途
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
		$searchResult = $goods->getGoodsWithKeyWords($keywords);
		$this->assign($goods->getDataName(), $searchResult);
		$this->assign('keywords', $keywords);
		$this->display();
		// $goods = D('Goods');
		// if($goods->add()) {
		// 	$this->success("s");
		// }
		// else {
		// 	echo ($goods->getError());
		// 	echo 3;
		// }
    }
}
