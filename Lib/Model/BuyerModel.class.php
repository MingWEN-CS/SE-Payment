<?php
class BuyerModel extends Model{
	protected $_map = array(
		'uid' => 'UID',
		'pwd2' => 'PASSWDPAYMENT',
	);

	protected $_validate = array(
	);

	protected $_auto = array(
		array('PASSWDPAYMENT','md5',3,'function'),
	);

	public function modifyCredit($uid,$credit){
		$condition['UID'] = $uid;
		$user = $this->where($condition)->find();
		$newCredit = $user['CREDIT'] + $credit;
		$this->where($condition)->setField('CREDIT',$newCredit);
		if ($newCredit > 2000){
			$this->where($condition)->setField('VIP',1);
		}
	}

}
?>
