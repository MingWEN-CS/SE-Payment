<?php
class OrderOperationModel extends Model{
	public function addOperation($oid, $operation, $operator){
		$data['OID'] = $oid;
		$data['OPERATION'] = $operation;
		//$data['time'] = date('Y-m-d H:i:s', time());
		$data['OPERATOR'] = $operator;
		$this->add($data);
	}
    public function getcreatetime($oid){
        $condition['OID']=$oid;
        $condition['OPERATION']="created";
        $result=$this->where($condition)->find();
        return $result['TIME'];
    }
    public function getpaytime($oid){
        $condition['OID']=$oid;
        $condition['OPERATION']="pay";
        $result=$this->where($condition)->find();
        return $result['TIME'];
    }
    public function getshiptime($oid){
        $condition['OID']=$oid;
        $condition['OPERATION']="shipping";
        $result=$this->where($condition)->find();
        return $result['TIME'];
    }
    public function getconfirmtime($oid){
        $condition['OID']=$oid;
        $condition['OPERATION']="confirm_receipt";
        $result=$this->where($condition)->find();
        return $result['TIME'];
    }
    public function getoptime($oid){
        $time['create']=$this->getcreatetime($oid);
        $time['pay']=$this->getpaytime($oid);
        $time['ship']=$this->getshiptime($oid);
        $time['confirm']=$this->getconfirmtime($oid);
        return $time;
    }
}
