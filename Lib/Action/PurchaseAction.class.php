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
		$this->assign('general_goods_sort_options', GeneralGoodsModel::getSortFieldArray());
		$this->assign('hotel_room_sort_options', AirplaneTicketModel::getSortFieldArray());
		$this->assign('airplane_ticket_sort_options', HotelRoomModel::getSortFieldArray());
		$this->assign('general_goods_source_place', SourcePlace::getSourcePlaceObjectsArray());
		$this->display();
    }
	
	public function shoppingcart() {
		$case_template = 'CASE '.
			'WHEN type = 1 THEN general_goods.${ph} '.
			'WHEN type = 2 THEN hotel_room.${ph} '.
			'WHEN type = 3 THEN airplane_ticket.${ph} '.
		'END ';
		$model = new Model();
		$user_id = 1;
		$array['list'] = $model->query('SELECT '.str_replace('${ph}', 'name', $case_template).'as name, good_id, good_count, '.
			str_replace('${ph}', 'price', $case_template).
			'as price '.
			'FROM shopping_cart, goods, general_goods, hotel_room, airplane_ticket '.
			'WHERE user_id = '.$user_id.' AND good_id = '.
			str_replace('${ph}', 'id', $case_template));
			//'GROUP BY name WITH ROLLUP');
		$array['static'] = $model->query('SELECT sum(good_count) as count, sum('.
			str_replace('${ph}', 'price', $case_template).
			' * good_count) as price '.
			'FROM shopping_cart, goods, general_goods, hotel_room, airplane_ticket '.
			'WHERE user_id = '.$user_id.' AND good_id = '.
			str_replace('${ph}', 'id', $case_template));
		$this->assign($array);
		$this->display();
    }
	
	public function shoppingcartdelete() {
		$cart = D('ShoppingCart');
		$ret = true;
		foreach ($_POST['good_ids'] as $good_id) {
			$ret &= $cart->where('user_id = '.$_POST['user_id'].' AND good_id = '.$good_id)->delete();
		}
		if ($ret) {
			$this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}
	
	public function shoppingcartmodify() {
		$cart = D('ShoppingCart');
		//$this->success($_POST['add']);
		if ($cart->modifyCount($_POST['user_id'], $_POST['good_id'], $_POST['add'])) {
			$this->success('修改成功');
		} else {
			$this->error('修改失败');
		}
	}
	
}
?>
