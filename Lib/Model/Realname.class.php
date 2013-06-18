<?php
class RealnameModel extends Model{
	Public verify($id, $name) {
        $condition['id'] = $id;
        $select_name = $this->where($condition)->find();
        if ($select_name == $name) return 1;
        else return 0;
	}
}
?>
