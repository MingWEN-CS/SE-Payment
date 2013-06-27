<?php
import("@.Org.Net.UploadFile");

class SellAction extends Action {
	
    public function index($info = null, $good_type = 'general-goods'){
		if (!$info)
			$info = $_POST['info'];
		if (!$good_type)
			$good_type = $_POST['good_type'];
		$user_id = $_SESSION['uid'];
		$seller = D('Seller');
		if (!$user_id || !$seller->where('UID = '.$user_id)->find())
			$this->error('To sell goods, you must login as a seller!');
		$general = D('GeneralGoods');
		$hotel = D('HotelRoom');
		$airplane = D('AirplaneTicket');
		$filter = 'seller_id = '.$user_id;
		$this->assign('good_type', $good_type);
		$goods = $general->where($filter)->select();
		for($i = 0;$i < count($goods);$i++){
			$goods[$i]['score'] = round($goods[$i]['score'], 2);
		}
		$this->assign('general_goods', $goods);
		$goods = $hotel->where($filter)->select();
		for($i = 0;$i < count($goods);$i++){
			$goods[$i]['score'] = round($goods[$i]['score'], 2);
		}
		$this->assign('hotel_room', $goods);
		$goods = $airplane->where($filter)->select();
		for($i = 0;$i < count($goods);$i++){
			$goods[$i]['score'] = round($goods[$i]['score'], 2);
		}
		$this->assign('airplane_ticket', $goods);
		$this->assign('info', $info);
		
		$this->display('index');
    }
	
	public function add(){
		$good_type = $this->_param('good_type');
		$good_id = $this->_param('good_id');
		switch ($good_type) {
		case 'general-goods':
			$goods = D('GeneralGoods');
			$type = 1;
			break;
		case 'hotel-room':
			$goods = D('HotelRoom');
			$type = 2;
			break;
		case 'airplane-ticket':
			$goods = D('AirplaneTicket');
			$type = 3;
			break;
		default:
			$this->error("Unknown good's type!");
			break;
		}
		if (IS_POST) {
			if ($isCreate = !$good_id) {
				$data = array(
					'type' => $type,
				);
				$good_id = D('Goods')->add($data);
			}
			if ($goods->create()) {
				$goods->id = $good_id;
				$goods->seller_id = $_SESSION['uid'];
				// change time to timestamp
				switch ($good_type) {
				case 'general-goods':
					break;
				case 'hotel-room':
					$goods->date_time = strtotime($goods->date_time);
					break;
				case 'airplane-ticket':
					$goods->departure_date_time = strtotime($goods->departure_date_time);
					$goods->arrival_date_time = strtotime($goods->arrival_date_time);
					break;
				}
				if (!empty($_FILES)) {
					$upload = new UploadFile();
					$upload->maxSize = 1048576;
					$upload->allowExts = array('bmp', 'jpg', 'gif', 'png', 'jpeg');
					$upload->savePath = './Upload/';
					$upload->uploadReplace = true;
					if (!$upload->upload()) {
						$this->error($upload->getErrorMsg());
					} else {
						$info = $upload->getUploadFileInfo();
						$goods->image_uri = $info[0]['savename'];
					}
				}
				if ($isCreate) {
					$goods->add();
				} else {
					$goods->save($data);
				}
				$this->success('Operation success!');
			} else {
				$this->error($goods->getError());
			}
		} else {
			if ($good_id) {
				$good = $goods->where('id = '.$good_id)->find();
				switch ($good_type) {
				case 'general-goods':
					break;
				case 'hotel-room':
					$good['date_time_selector'] = date('d F Y - h:i a', $good['date_time']);
					$good['date_time'] = date('Y-m-d H:i:s', $good['date_time']);
					break;
				case 'airplane-ticket':
					$good['departure_date_time_selector'] = date('d F Y - h:i a', $good['departure_date_time']);
					$good['departure_date_time'] = date('Y-m-d H:i:s', $good['departure_date_time']);
					$good['arrival_date_time_selector'] = date('d F Y - h:i a', $good['arrival_date_time']);
					$good['arrival_date_time'] = date('Y-m-d H:i:s', $good['arrival_date_time']);
					break;
				}
				$this->assign('good', $good);
			}
			$this->assign('good_type', $good_type);
			
			// 3 kinds goods' source place options
			$this->assign('source_place', GeneralGoodsModel::getSourcePlaceObjectsArray());
			$this->assign('airplane_ticket_departure_place', AirplaneTicketModel::getSourcePlaceObjectsArray());
			$this->assign('airplane_ticket_arrival_place', AirplaneTicketModel::getArrivalPlaceObjectsArray());
			// hotel suit options
			$this->assign('hotel_room_suit', HotelRoomModel::getHotelRoomSuitArray());
			// airplane carbin options
			$this->assign('airpalne_ticket_carbin', AirplaneTicketModel::getAirplaneTicketCarbinArray());
			
			$this->display();
		}
	}
	
	public function upload() {
		$this->error('ge');
	}
	
	public function delete(){
		$goods = D('Goods');
		$ret = true;
		print_r($_POST['good_ids']);
		foreach ($_POST['good_ids'] as $good_id) {
			$ret &= $goods->where('id = '.$good_id)->delete();
		}

		$this->redirect('Sell/index');
		// $this->index($ret ? 'Deletion succeeded!' : 'Deletion failed!', $_POST['good_type']);
	}
	
}
?>
