<?php
class GoodsHelper {
	static $allKindsGoods = null;
	static public function getBasicGoodsInfoOfId($id){
		if(!$allKindsGoods) {
			$allKindsGoods = array(D('GeneralGoods'), D('HotelRoom'), D('AirplaneTicket'));
		}
		foreach($allKindsGoods as $eachKind) {
			if(($good = $eachKind->findGoodsWithId($id))) {
				return $good;
			}
		}
		return null;
	}
}
?>
