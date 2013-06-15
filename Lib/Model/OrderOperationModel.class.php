<?php
class OrderOperationModel extends Model{
	public function addOperation($oid, $operation, $operator){
		$data['OID'] = $oid;
		$data['OPERATION'] = $operation;
		//$data['time'] = date('Y-m-d H:i:s', time());
		$data['OPERATOR'] = $operator;
		$this->add($data);
	}
}
