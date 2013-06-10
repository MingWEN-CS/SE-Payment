<?php
class GoodsModel extends Model{
	public function addGoods(){
		return $this->add();
	}
	protected $_auto = array(
		array('id'),
	);
}
?>