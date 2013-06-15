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
		$filter = '';//'seller_id = '.$user_id;
		$this->assign('good_type', $good_type);
		$this->assign('general_goods', $general->where($filter)->select());
		$this->assign('hotel_room', $hotel->where($filter)->select());
		$this->assign('airplane_ticket', $airplane->where($filter)->select());
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
				//change time to timestamp
				if ($goods->getType() == HotelRoomModel::getType()) {
					$goods->date_time = strtotime($goods->date_time);
				}
				else if ($goods->getType() == AirplaneTicketModel::getType()) {
					$goods->departure_date_time = strtotime($goods->departure_date_time);
					$goods->arrival_date_time = strtotime($goods->arrival_date_time);
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
			if ($good_id)
				$this->assign('good', $goods->where('id = '.$good_id)->find());
			$this->assign('good_type', $good_type);
			
			//3 kinds goods' source place options
			$this->assign('source_place', GeneralGoodsModel::getSourcePlaceObjectsArray());
			$this->assign('airplane_ticket_departure_place', AirplaneTicketModel::getSourcePlaceObjectsArray());
			$this->assign('airplane_ticket_arrival_place', AirplaneTicketModel::getArrivalPlaceObjectsArray());
			//hotel suit options
			$this->assign('hotel_room_suit', HotelRoomModel::getHotelRoomSuitArray());
			//airplane carbin options
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
		$this->index($ret ? 'Deletion succeeded!' : 'Deletion failed!', $_POST['good_type']);
	}
	
}
?>
