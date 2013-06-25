<?php
class AddressModel extends Model{

	protected $_map = array(
		'uid' => 'UID',
		'province' => 'PROVINCE',
		'city' => 'CITY',
		'district' => 'STRICT',
		'street' => 'STREET',
	);

	protected $_validate = array(
		array('PROVINCE','require','province information is necessary',1),
		array('CITY','require','city information is necessary',1),
		array('STRICT','require','district information is necessary',1),
		array('STREET','require','street is necessary',1),
	);

	public function findAddressById($id){
		return $this->where('UID ='.$id)->select();
	}
}

?>