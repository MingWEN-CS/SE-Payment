<?php
import("@.Util.Goods.SourcePlace");
import("@.Util.Goods.GoodsHelper");

class ShoppingCartAction extends Action {
	
	public function index($info = null) {
		$user_id = $this->_session('uid');
		$prefix = C('DB_PREFIX');
		$buyer = M('Buyer');
		if (!$user_id || !$buyer->where('uid = '.$user_id)->find())
			$this->error('To use shopping cart, you must login as a buyer!');
		$prefix = C('DB_PREFIX');
		$case_template = 'CASE '.
			'WHEN type = 1 THEN '.$prefix.'general_goods.${ph} '.
			'WHEN type = 2 THEN '.$prefix.'hotel_room.${ph} '.
			'WHEN type = 3 THEN '.$prefix.'airplane_ticket.${ph} '.
		'END ';
		$shoppingCart = M('ShoppingCart');
		$itemArray = $shoppingCart->where('se_user_id = ' . $user_id)->select();
		$resultArray = array();
		$static = array('price' => 0, 'count' => 0);
		foreach($itemArray as $item) {
			$newGoods = GoodsHelper::getBasicGoodsInfoOfId($item[good_id]);
			$resultArray = array_merge($resultArray, array(array_merge($item, $newGoods)));
			$static['price'] += $newGoods[price];
			$static['count'] ++;
		}
		
		
		// $model = new Model();
		$this->assign('list', $resultArray);
		
		// $this->assign('list', $model->query('SELECT '.str_replace('${ph}', 'name', $case_template).'as name, good_id, good_count, '.
		// 	str_replace('${ph}', 'price', $case_template).
		// 	'as price '.
		// 	'FROM '.$prefix.'shopping_cart, '.$prefix.'goods, '.$prefix.'general_goods, '.$prefix.'hotel_room, '.$prefix.'airplane_ticket '.
		// 	'WHERE se_user_id = '.$user_id.' AND good_id = '.
		// 	str_replace('${ph}', 'id', $case_template)));
			//'GROUP BY name WITH ROLLUP');
		$this->assign('static', $static);
		// $this->assign('static', $model->query('SELECT sum(good_count) as count, sum('.
		// 	str_replace('${ph}', 'price', $case_template).
		// 	' * good_count) as price '.
		// 	'FROM '.$prefix.'shopping_cart, '.$prefix.'goods, '.$prefix.'general_goods, '.$prefix.'hotel_room, '.$prefix.'airplane_ticket '.
		// 	'WHERE se_user_id = '.$user_id.' AND good_id = '.
		// 	str_replace('${ph}', 'id', $case_template)));
		$this->assign('info', $info);
		$this->display('index');
    }
	
	public function delete() {
		$cart = D('ShoppingCart');
		$ret = true;
		foreach ($_POST['good_ids'] as $good_id) {
			$ret &= $cart->where('user_id = '.$this->_session('uid').' AND good_id = '.$good_id)->delete();
		}
		$this->index($ret ? 'Deletion succeeded!' : 'Deletion failed!');
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
