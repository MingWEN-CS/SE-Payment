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
<<<<<<< HEAD
	
=======

	public function authenticate($id){
		return $this->where('UID ='.$id)->setField('AUTHENTICATED',1);
	}
>>>>>>> 30c6d2593392e76dfbaa2abf09532dd7d543fe1e
}
?>