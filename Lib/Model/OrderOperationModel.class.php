<?php
class OrderOperationModel extends Model{
	public function addOperation($oid, $operation, $operator){
		$data['oid'] = $oid;
		$data['operation'] = $operation;
		//$data['time'] = date('Y-m-d H:i:s', time());
		$data['operator'] = $operator;
		$this->add($data);
	}
}
