<?php
// 本类由系统自动生成，仅供测试用途
class AdminAction extends Action {
    
    public function index (){
        $this->display("login");
    }    

    public function login () {
    	$se_admin = D('Admin');
        $name = $this->_post('name');
        $password = $this->_post('password');
        $condition['name'] = $name;
        $admin = $se_admin->where($condition)->find();
    	if($password == $admin['password']) {
    		$this->display('index');
    	}
        else {
            $this->display();
        }
    }
}
?>