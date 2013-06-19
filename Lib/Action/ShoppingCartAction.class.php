<?php
import("@.Util.Goods.SourcePlace");
import("@.Util.Goods.GoodsHelper");
import("@.Util.User.BuyerHelper");
import("@.Util.CommonValue");

class ShoppingCartAction extends Action {
	
	// main shopping cart page, show all goods in user's cart
	// also provide operations such as delete, modify quantity
	public function index($info = null) {
		// get user id
		$user_id = $this->_session('uid');
		$buyer = M('Buyer');
		// check if logged in and if this user is a buyer
		if (!$user_id || !$buyer->where('uid = '.$user_id)->find())
			// if not, redirecting...
			$this->error('To use shopping cart, you must login as a buyer!', 'redir');
		$shoppingCart = M('ShoppingCart');
		// select from shopping cart database, get this user's cart
		$itemArray = $shoppingCart->where('user_id = ' . $user_id)->select();
		$resultArray = array();
		// static info to get total count and total price
		// init static info
		$static = array('price' => 0, 'count' => 0);
		// check if this user is VIP
		$isVip = BuyerHelper::getIsVip($user_id);
		// tranverse the result to get static info
		foreach ($itemArray as $item) {
			$newGoods = GoodsHelper::getBasicGoodsInfoOfId($item[good_id]);
			// if a VIP
			if($isVip) {
				// reduce price by VIP discount
				$newGoods[price] = CommonValue::getVipDiscount() * $newGoods[price];
			}
			print_r($item);
			print_r($newGoods);
			$resultArray = array_merge($resultArray, array(array_merge($item, $newGoods)));
			// update static info
			$static['price'] += $newGoods[price] * $item[good_count];
			$static['count'] += $item[good_count];
		}
		$this->assign('list', $resultArray);
		$this->assign('static', $static);
		// show info from other pages
		$this->assign('info', $info);
		$this->display('index');
    }
	
	// all deletion will be POSTed here
	// with an array containg all good_id to be deleted as parameter
	public function delete() {
		$cart = D('ShoppingCart');
		$ret = true;
		// get good_id to delete from the POSTed array
		foreach ($_POST['good_ids'] as $good_id) {
			// delete each good
			$ret &= $cart->where('user_id = '.$this->_session('uid').' AND good_id = '.$good_id)->delete();
		}
		// return hint, show index page
		$this->index($ret ? 'Deletion succeeded!' : 'Deletion failed!');
	}
	
	// modification will be POSTed here
	// add 1 or sub 1 of the quantity of a good
	public function modify() {
		$cart = D('ShoppingCart');
		// call a method in shopping cart model
		// decide by return value to give different hint
		if ($cart->modifyCount($this->_session('uid'), $_POST['good_id'], $_POST['good_count'], $_POST['add'])) {
			$this->success('Modification succeeded!');
		} else {
			$this->error('Modification failed!');
		}
	}
	
}
?>
