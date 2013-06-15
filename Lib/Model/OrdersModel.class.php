<?php

class OrdersModel extends Model{
    public function findorderbyid($id){
        $condition['id']=$id;
        return $this->where($condition)->find();
    }
    public function searchidbyname($username){
            $condition['buyer']=$username;
            return $this->field('id')->where($condition)->select();
    }
    public function createorder($orderinfo){
            return $this->create($orderinfo)->add();
    }
}

?>
