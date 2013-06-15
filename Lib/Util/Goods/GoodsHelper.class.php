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
	
	static public function getGoodsTypeOfId($id){
		$goods = D('Goods');
		$goodsResult = $goods->where('id='.$id)->find();
		if($goodsResult[type] == 1) {
			return GeneralGoodsModel::getType();
		}
		else if($goodsResult[type] == 2) {
			return HotelRoomModel::getType();
		}
		else if($goodsResult[type] == 3) {
			return AirplaneTicketModel::getType();
		}
		return null;
	}
}
?>
