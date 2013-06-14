<?php

class OrdersModel extends Model{
    public function findorderbyid($id){
        $condition['id']=$id;
        return $this->where($condition)->find();
    }
}

?>
