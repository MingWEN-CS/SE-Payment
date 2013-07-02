<?php
class FeedbackModel extends Model{
	public function score_sale_update($goods_id){
		$score_set = $this->where('goods_id='.$goods_id)->select();
		$score_sum = 0;
		foreach($score_set as $score_each) {
			$score_sum += $score_each['score'];
		}
		$score_avg = $score_sum / count($score_set);
		$Goods = D('Goods');
		
		$goodsResult = $Goods->where('id='.$goods_id)->find();
		if($goodsResult['type'] == 1) {
			$GoodsAllKinds = D('GeneralGoods');
		}
		else if($goodsResult['type'] == 2) {
			$GoodsAllKinds =  D('HotelRoom');
		}
		else if($goodsResult['type'] == 3) {
			$GoodsAllKinds = D('AirplaneTicket');
		}
		$data['score'] = $score_avg;
		return $GoodsAllKinds->where('id='.$goods_id)->save($data);
	}
	
	protected $_auto = array(
		array('id'),
	);
}
?>
