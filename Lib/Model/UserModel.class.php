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

		return $this->where('ID ='.$id)->delete();
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
}
?>
