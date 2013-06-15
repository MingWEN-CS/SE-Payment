<?php

class OrdersModel extends Model{
    public function findorderbyid($id) {
        $condition['id']=$id;
        return $this->where($condition)->find();
    }
    public function searchIDbyBuyerName($username) {
            $condition['buyer']=$username;
            return $this->field('id')->where($condition)->select();
    }
	
	public function searchIDbySellerName($username) {
            $condition['seller']=$username;
            return $this->field('id')->where($condition)->select();
    }
	
	public function changeState($oid, $newState) {
		$condition['id'] = $oid;
		$data['state'] = $newState;
		return $this->where($condition)->save($data);
	}
	
	public function delete($oid) {
		$condition['id'] = $oid;
		$data['isdelete'] = 1;
		return $this->where($condition)->save($data);
	}
}

?>
