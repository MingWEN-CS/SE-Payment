<?php
class AdminModel extends Model{
	protected $_validate = array(
		array('name','require','admin name is necessary',1),
		array('name','','the admin name has been registered',1,'unique',1),
		array('password','require','Password is necessary',1),
	);

	public function addAdmin($name, $password, $info){
		$this->id = id;
		$this->name = name;
		$this->password = password;
		$this->info = info;
		return $this->add();
	}	

	public function selectAdmin(){
		return $this->select();
	}

	public function deleteAdmin($name){
		return $this->where('name = "'.$name.'"')->delete();
	}
}
?>

