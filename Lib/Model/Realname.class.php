<?php
class RealnameModel extends Model{
	Public function verify($id, $name) {
        $condition['id'] = $id;
        $select = $this->where($condition)->find();
        if ($select['name'] == $name) return 1;
        else return 0;
	}
}
?>
