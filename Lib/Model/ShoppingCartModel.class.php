<?php
class ShoppingCartModel extends Model{
	
	public function modifyCount($user_id, $good_id, $good_count, $add) {
		$good = $this->where('user_id = '.$user_id.' AND good_id = '.$good_id);
		if ($add == 'true')
			// check whether stock is enough
			if (getStockById($good_id) < $good_count)
				return $good->setInc('good_count');
			else
				return false;
		else
			// count cannot be 0
			if ($good_count > 1)
				return $good->setDec('good_count');
			else
				return false;
	}
}
?>