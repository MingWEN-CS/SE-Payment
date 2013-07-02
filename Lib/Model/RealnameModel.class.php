<?php
class RealnameModel extends Model{
	Public function verify($id, $name) {
        $condition['ID'] = $id;
        $card = $this->where($condition)->find();
        $select_name = $card['NAME'];
        if ($select_name == $name) return true;
        else return false;
	}
}
?>
