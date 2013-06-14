<?php
import("@.Util.Goods.SourcePlace");

class SellAction extends Action {
	
    public function index(){
		$user_id = $_SESSION['uid'];
		$seller = M('Seller');
		//if (!$user_id || !$seller->where('userid = '.$user_id)->find())
		//	$this->error('To sell goods, you must login as a seller!');
		$general = D('GeneralGoods');
		$hotel = D('HotelRoom');
		$airplane = D('AirplaneTicket');
		$filter = '';//'seller_id = '.$user_id;
		$this->assign('general_goods', $general->where($filter)->select());
		$this->assign('hotel_room', $hotel->where($filter)->select());
		$this->assign('airplane_ticket', $airplane->where($filter)->select());
		$this->display('index');
    }
	
	public function add(){
		$good_type = $this->_param('good_type');
		$good_id = $this->_param('good_id');
		switch ($good_type) {
		case 'general-goods':
			$goods = M('GeneralGoods');
			break;
		case 'hotel-room':
			$goods = M('HotelRoom');
			break;
		case 'airplane-ticket':
			$goods = M('AirplaneTicket');
			break;
		default:
			$this->error("Unknown good's type!");
			break;
		}
		if (IS_POST) {
			if (!$good_id) {
				$data = array(
					'type' => $good_type,
				);
				$good_id = D('Goods')->add($data);
			}
			switch ($good_type) {
			case 'general-goods':
				$data = array(
					'name' => $name,
					'price'  => $pwd,
					'seller_id' => $_SESSION['uid'],
					'bought_count' => $type,
					'score' => 0,
					'score_count' => 0,
					'place' => $place,
					'image_uri' => null,
					'stock' => $stock,
					'description' => $descripton,
				);
				break;
			case 'hotel-room':
				$data = array(
					'name' => $name,
					'pwd'  => $pwd,
					'email' => $email,
					'type' => $type,
					'phone' => $phone,
				);
				break;
			case 'airplane-ticket':
				$data = array(
					'name' => $name,
					'pwd'  => $pwd,
					'email' => $email,
					'type' => $type,
					'phone' => $phone,
				);
				break;
			}
            print_r($data);
		} else {
			if ($good_id)
				$this->assign('content', $goods->where('id = '.$good_id)->find());
			$this->display();
		}
	}
	
	public function delete(){
		$goods = D('Goods');
		$ret = true;
		foreach ($_POST['good_ids'] as $good_id) {
			$ret &= $goods->where('id = '.$good_id)->delete();
		}
		$this->index();
	}
	
}
?>
