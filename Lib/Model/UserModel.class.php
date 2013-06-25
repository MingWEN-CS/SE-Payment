<?php
class UserModel extends Model{
	protected $_map = array(
		'name' => 'USERNAME',
		'pwd' => 'PASSWD',
		'email' => 'EMAIL',
		'phone' => 'PHONE',
		'type' => 'TYPE',
		'balance' => 'BALANCE'
	);

	protected $_validate = array(
		array('USERNAME','require','Username is necessary',1),
		array('USERNAME','','the username has been registered',1,'unique',1),
		array('EMAIL','require','Email is necessary',1),
		array('EMAIL','email','Email Format Error',1),	
		array('PASSWD','require','Password is necessary',1),
	);

	protected $_auto = array(
		array('PASSWD','md5',3,'function'),
	);

	public function addUser($username, $password, $email){
		$this->NAME = $username;
		$this->PASSWORD = $password;
		$this->EMAIL = $email;
		return $this->add();
	}	
	
	public function selectUser(){
		return $this->select();
	}

	public function findUserByName($name){
		return $this->where('USERNAME ="' . $name .'"')->find();
	}

	public function selectUserByName($username){
		return $this->where('NAME = '.$username)->select();
	}

	public function deleteUserById($id){

		return $this->where('UID ='.$id)->delete();
	}

	public function checkEmail($email, $type){
		$user = $this->where('EMAIL = "'. $email .'" and TYPE = '.$type)->find();
		
		$typeName = '';
		if ($type == 0) $typeName = " buyer ";
		else if ($type == 1) $typeName = " seller ";
		
		if (!empty($user)){
			$msg = "The Email has albready been registered as a" .$typeName;
			return $msg;
		}else return NULL;
	}

	public function setPhone($id,$phone){
		return $this->where('UID ='.$id)->setField('PHONE',$phone);
	}

	public function modifyPhone($id,$phone){
		return $this->where('UID ='.$id)->setField('PHONE',$phone);	
	}

	public function checkUser($useremail,$password){
		$user = $this->where('EMAIL = "'.$useremail.'"')->find();
		if (!$user){
			return -1;
		}
		else {
			if (md5($password) != $user['PASSWORD']){
				return 0;
			}
			else return 1;
		}
	}

	public function authenticate($id,$name,$iden){
		$this->where('UID ='.$id)->setField('AUTHENTICATED',1);
		$this->where('UID ='.$id)->setField('REALNAME',$name);
		$this->where('UID ='.$id)->setField('IDENTITY',$iden);
		return true;
	}

    public function moneyTransfer($payerid,$receiver,$money){
        $payercondition['UID']=$payerid;
        $receivercondition['UID']=$receiver;
        $payerbalance=$this->where($payercondition)->field('BALANCE')->find();
        $receiverbalance=$this->where($receivercondition)->field('BALANCE')->find();
        $payernewdata['BALANCE']=$payerbalance['BALANCE']-$money;
        $receivernewdata['BALANCE']=$receiverbalance['BALANCE']+$money;
        $this->where($payercondition)->save($payernewdata);
        $this->where($receivercondition)->save($receivernewdata);

    }
}
?>
