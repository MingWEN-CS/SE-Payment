<?php
class CardModel extends Model{
	Public function verify($id, $password) {
        $condition['id'] = $id;
        $select = $this->where($condition)->find();
        if ($select['password'] == $password) return 1;
        else return 0;
	}
}
?>
