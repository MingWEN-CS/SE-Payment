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

	public function authenticate($id){
		return $this->where('UID ='.$id)->setField('AUTHENTICATED',1);
	}
}
?>
