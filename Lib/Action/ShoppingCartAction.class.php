<?php
import("@.Util.Goods.SourcePlace");

class ShoppingCartAction extends Action {
	
	public function index() {
		if (!isset($_SESSION['uid'])) {
			$this->display('redir');
		} else {
			$user_id = $this->_session('uid');
			$case_template = 'CASE '.
				'WHEN type = 1 THEN general_goods.${ph} '.
				'WHEN type = 2 THEN hotel_room.${ph} '.
				'WHEN type = 3 THEN airplane_ticket.${ph} '.
			'END ';
			$model = new Model();
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
    }
	
	public function delete() {
		$cart = D('ShoppingCart');
		$ret = true;
		foreach ($_POST['good_ids'] as $good_id) {
			$ret &= $cart->where('user_id = '.$this->_session('uid').' AND good_id = '.$good_id)->delete();
		}
		if ($ret) {
			$this->success('Deletion succeeded!');
		} else {
			$this->error('Deletion failed!');
		}
	}
	
	public function modify() {
		$cart = D('ShoppingCart');
		//$this->success($_POST['add']);
		if ($cart->modifyCount($this->_session('uid'), $_POST['good_id'], $_POST['add'])) {
			$this->success('Modification succeeded!');
		} else {
			$this->error('Modification failed!');
		}
	}
	
}
?>
