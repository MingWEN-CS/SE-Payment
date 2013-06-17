<?php
class SellerModel extends Model{
	protected $_map = array(
		'uid' => 'UID',
		'pwd2' => 'PASSWDCONSIGN',
	);

	protected $_validate = array(
	);

	protected $_auto = array(
		array('PASSWDCONSIGN','md5',3,'function'),
	);
}
?>