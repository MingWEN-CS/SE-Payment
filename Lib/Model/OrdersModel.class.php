<?php

class OrdersModel extends Model{
    public function findorderbyid($id) {
        $condition['ID']=$id;
        return $this->where($condition)->find();
    }
    public function searchIDbyBuyerName($username) {
            $condition['BUYER']=$username;
            return $this->field('ID')->where($condition)->select();
    }

public function searchIDbySellerName($username) {
            $condition['SELLER']=$username;
            return $this->field('ID')->where($condition)->select();
    }

public function changeState($oid, $newState) {
$condition['ID'] = $oid;
$data['STATE'] = $newState;
return $this->where($condition)->save($data);
}


public function delete($oid) {
$condition['ID'] = $oid;
$data['ISDELETE'] = 'YES';
return $this->where($condition)->save($data);
}

public function audited($oid) {
$condition['ID'] = $oid;
$data['ISAUDIT'] = 'YES';
return $this->where($condition)->save($data);
}

    public function insertneworder($order){
         $this->create($order);
         return $this->add();
    }
}

?>
