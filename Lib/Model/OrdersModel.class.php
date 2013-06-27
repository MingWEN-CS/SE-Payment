<?php

class OrdersModel extends Model{
    public function findorderbyid($id) {
        $condition['ID']=$id;
        return $this->where($condition)->find();
    }
    public function searchIDbyBuyerName($username) {
        $condition['BUYER']=$username;
        return $this->where($condition)->select();
    }

    public function searchIDbySellerName($username) {
        $condition['SELLER']=$username;
        return $this->where($condition)->select();
    }

    public function changeState($oid, $newState) {
        $condition['ID'] = $oid;
        $data['STATE'] = $newState;
		//>>modify the stock and brought count of goods. by group3
		if($newState == 'canceled') {
			$deleteGoods = D('OrderGoods')->searchbyid($oid);
			foreach($deleteGoods as $good) {
				D('Goods')->changeStock($good['GID'], $good['AMOUNT']);
			}
		}
		//<<
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
