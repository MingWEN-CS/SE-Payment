<?php
// 本类由系统自动生成，仅供测试用途
class AdminAction extends Action {
    
    public function index (){
        $this->display("login");
    }    

    public function login () {
    	$admin = D('Admin');
    	$isCorrect = $admin->verifyPassword($this->_post('name'), $this->_post('password'));
    	if($isCorrect == 1) {
    		$this->display('header');
    	}

    }
}
?>