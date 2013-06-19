<?php
class GoodsModel extends Model{
	public function addGoods(){
		return $this->add();
	}
	protected $_auto = array(
		array('id'),
	);
	
	//$count: negative means decrease and positive means increase
	public function changeStock($id, $count){
		$search_result = $this->where('id='.$id)->find();
		$goods_type = $search_result['type'];
		switch($goods_type) {
		case '1':
			$GoodsAllKinds = D('GeneralGoods');
			break;
		case '2':
			$GoodsAllKinds = D('HotelRoom');
			break;
		case '3':
			$GoodsAllKinds = D('AirplaneTicket');
			break;
		default:
			return 0;
		}
		return($GoodsAllKinds->where('id='.$id)->setInc('stock', $count) &&
			$GoodsAllKinds->where('id='.$id)->setDec('bought_count', $count));
	}
}
?>
