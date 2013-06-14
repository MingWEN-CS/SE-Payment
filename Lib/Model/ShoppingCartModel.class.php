<?php
class ShoppingCartModel extends Model{
	
	public function modifyCount($user_id, $good_id, $add) {
		$temp = $this->where('user_id = '.$user_id.' AND good_id = '.$good_id);
		if ($add == 'true')
			return $temp->setInc('good_count');
		else
			return $temp->setDec('good_count');
	}
	
}
?>