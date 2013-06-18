<?php
class ShoppingCartModel extends Model{
	
	public function addItem($user_id, $good_id, $good_count) {
		$this->USER_ID = $user_id;
		$this->GOOD_ID = $good_id;
		$this->GOOD_COUNT = $good_count;
		return $this->add();
	}
	
	public function modifyCount($user_id, $good_id, $add) {
		$temp = $this->where('user_id = '.$user_id.' AND good_id = '.$good_id);
		if ($add == 'true')
			return $temp->setInc('good_count');
		else
			return $temp->setDec('good_count');
	}
	
}
?>