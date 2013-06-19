<?php
class ShoppingCartModel extends Model{
	
	public function modifyCount($user_id, $good_id, $good_count, $add) {
		$temp = $this->where('user_id = '.$user_id.' AND good_id = '.$good_id);
		if ($add == 'true')
			return $temp->setInc('good_count');
			// if ($temp->getField('stock') > $good_count)
			// 	return $temp->setInc('good_count');
			// else
			// 	return false;
		else
			return $temp->setDec('good_count');
			// if ($good_count > 1)
			// 	return $temp->setDec('good_count');
			// else
			// 	return false;
	}
}
?>