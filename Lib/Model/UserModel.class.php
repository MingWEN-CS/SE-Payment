<?php
class UserModel extends Model{
	
	public function addUser($username, $password, $email){
		$this->NAME = $username;
		$this->PASSWORD = $password;
		$this->EMAIL = $email;
		return $this->add();
	}	

	public function selectUser(){
		return $this->select();
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