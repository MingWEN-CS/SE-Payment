<?php
	class UsercardModel extends Model{
	protected $_map = array(
		'userId' => 'USERID',
		'cardId' => 'CARDID',
	);

	protected $_validate = array(
		array('USERID','require','Userid is necessary',1),
		array('CARDID','require','Card id is necessary',1),
		);
	}
?>