<?php
class CardModel extends Model{
<<<<<<< HEAD
	Public function verify($id, $password) {
        $condition['id'] = $id;
        $select = $this->where($condition)->find();
        if ($select['password'] == $password) return 1;
        else return 0;
=======
	public function verify($id, $name) {
        $condition['ID'] = $id;
        $user = $this->where($condition)->find();
        $select_name = $user['PASSWD'];
        if ($select_name == $name) return true;
        else return false;
>>>>>>> 5d96420f566734bd8239432783e09f927266fc19
	}
}
?>
