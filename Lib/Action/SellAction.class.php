<?php
import("@.Org.Net.UploadFile");

class SellAction extends Action {
	
	// index page, to show all goods this seller sell
    public function index($info = null, $good_type = 'general-goods'){
		// if there is info to output
		if (!$info)
			$info = $_POST['info'];
		// get good_type, switch to corresponding tab
		if (!$good_type)
			$good_type = $_POST['good_type'];
		$user_id = $_SESSION['uid'];
		$seller = D('Seller');
		// check if a valid seller
		if (!$user_id || !$seller->where('UID = '.$user_id)->find())
			$this->error('To sell goods, you must login as a seller!');
		// output all goods the seller sell
		$general = D('GeneralGoods');
		$hotel = D('HotelRoom');
		$airplane = D('AirplaneTicket');
		$filter = 'seller_id = '.$user_id;
		$this->assign('good_type', $good_type);
		// find all general_goods
		$goods = $general->where($filter)->select();
		for($i = 0;$i < count($goods);$i++){
			$goods[$i]['score'] = round($goods[$i]['score'], 2);
		}
		$this->assign('general_goods', $goods);
		// find all hotel
		$goods = $hotel->where($filter)->select();
		for($i = 0;$i < count($goods);$i++){
			$goods[$i]['score'] = round($goods[$i]['score'], 2);
		}
		$this->assign('hotel_room', $goods);
		// find all airplane
		$goods = $airplane->where($filter)->select();
		for($i = 0;$i < count($goods);$i++){
			$goods[$i]['score'] = round($goods[$i]['score'], 2);
		}
		$this->assign('airplane_ticket', $goods);
		$this->assign('info', $info);
		
		$this->display('index');
    }
	
	// add a good or modify an existed one
	public function add(){
		$good_type = $this->_param('good_type');
		$good_id = $this->_param('good_id');
		// open correct database by given good type
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
		// POST means sumbit the form
		// i.e. add or update good info
		if (IS_POST) {
			// if add a new good
			if ($isCreate = !$good_id) {
				$data = array(
					'type' => $type,
				);
				// add into good table and get it's id
				$good_id = D('Goods')->add($data);
			}
			// if successly created(or prepared to update) a record by POSTed info
			if ($goods->create()) {
				// filling filed which are not been POSTed
				$goods->id = $good_id;
				$goods->seller_id = $_SESSION['uid'];
				// change time to timestamp, string formating
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
				// dealing with uploaded picture
				if (!empty($_FILES)) {
					$upload = new UploadFile();
					// setting maximum upload file size
					$upload->maxSize = 1048576;
					// setting allowed format
					$upload->allowExts = array('bmp', 'jpg', 'gif', 'png', 'jpeg');
					// setting upload path
					$upload->savePath = './Upload/';
					$upload->uploadReplace = true;
					// if upload failed
					if (!$upload->upload()) {
						$this->error($upload->getErrorMsg());
					// upload success
					} else {
						$info = $upload->getUploadFileInfo();
						// save picture name to uri field
						$goods->image_uri = $info[0]['savename'];
					}
				}
				// add or update records
				if ($isCreate) {
					$goods->add();
				} else {
					$goods->save($data);
				}
				$this->success('Operation success!');
			// create record failed
			} else {
				$this->error($goods->getError());
			}
		// GET means auto fill good info
		// i.e. using modify function
		} else {
			// if it's a modify rather than add a new one
			if ($good_id) {
				$good = $goods->where('id = '.$good_id)->find();
				// do time formating, from timestamp to string
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
				// fill goods info to form
				$this->assign('good', $good);
			}
			// set good type
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
	
	// delete some goods, take an array as parameter
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
