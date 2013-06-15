<?php

class OrdersModel extends Model{
    public function findorderbyid($id){
        $condition['ID']=$id;
        return $this->where($condition)->find();
    }
    public function searchidbyname($username){
            $condition['BUYER']=$username;
            return $this->field('ID')->where($condition)->select();
    }
    public function createorder($orderinfo){
            return $this->create($orderinfo)->add();
    }
}

?>
