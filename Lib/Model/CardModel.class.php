<?php
class CardModel extends Model{

	public function verify($id, $name) {
        $condition['ID'] = $id;
        $user = $this->where($condition)->find();
        $select_name = $user['PASSWD'];
        if ($select_name == $name) return true;
        else return false;
}
?>
