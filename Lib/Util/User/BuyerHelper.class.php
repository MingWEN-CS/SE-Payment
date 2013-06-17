<?php	
class BuyerHelper {
	static function getIsVip($id) {
		$buyer = D('Buyer');
		$condition['UID'] = $id;
		$condition['VIP'] = 1;
		if($buyer->where($condition)->find()) {
			return 1;
		}
		else {
			return 0;
		}
	}
}
?>