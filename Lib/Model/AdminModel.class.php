<?php
class AdminModel extends Model{
	protected $_validate = array(
		array('name','require','admin name is necessary',1),
		array('name','','the admin name has been registered',1,'unique',1),
		array('password','require','Password is necessary',1),
	);

	public function addAdmin($name, $password, $info){
		$data['name'] = $name;
		$data['password'] = $password;
		$data['info'] = $info;
		return $this->add($data);
	}	

	public function selectAdmin($id, $name, $info){
		if ($id) {
			$condition['id'] = $id;
		}
		if ($name) {
			$condition['name'] = $name;
		}
		if ($info) {
			$condition['info'] = $info;
		}
		return $this->where($condition)->select();
	}

	public function deleteAdmin($id){
		$condition['id'] = $id;
		return $this->where($condition)->delete();
	}
}
?>

