<?php
// 本类由系统自动生成，仅供测试用途
class AdminAction extends Action {
    
    Public function login() {
        if(IS_AJAX) {
            $adminname = $this->_post('adminname');
            $condition['name'] = $adminname;
            $password = $this->_post('password');
            $se_admin = D("Admin");
            $admin = $se_admin->where($condition)->find();
            if (!$admin){
                $this->ajaxReturn('', 'Admin Does Not Exsit', 0);
            }
            else if ($password != $admin[password]){
                $this->ajaxReturn('', 'Wrong Password', 0);
            }
            else {
                $this->ajaxReturn('', 'Login Successfully', 1);
            }
        }
        else {
            $this->display();
        }
    }

    Public function index() {
        $this->display();
    }

    // Public function test() {
    //     $this->display();
    //     $DB = D('Admin');
    //     //insert
    //     $data['name'] = "ddl";
    //     $data['password'] = '123';
    //     $data['info'] = "test";
    //     $DB->add($data);
    //     //select
    //     $condition['name'] = "root";
    //     $result = $DB->where($condition)->select();
    //     var_dump($result);
    //     // //delete
    //     // $condition['name'] = "ddl";
    //     // $DB->where($condition)->delete();
    // }

    Public function postAdd() {
        $database = $this->_post('database');
        $DB = D($database);
        if ($database == "Admin") {
            $data['name'] = $this->_post('name');
            $data['password'] = $this->_post('password');
            $data['info'] = $this->_post('info');;
        }
        if ($database == "User") {
            $data['USERNAME'] = $this->_post('name');
            $data['PASSWD'] = $this->_post('password');
            $data['EMAIL'] = $this->_post('email');
            $data['TYPE'] = $this->_post('type');
            $data['BALANCE'] = $this->_post('balance');
            $data['PHONE'] = $this->_post('phone');
            $data['VIP'] = $this->_post('vip');
            $data['BLACKLIST'] = $this->_post('blacklist');
        }
        $status = $DB->add($data);
        if ($status) $this->ajaxReturn('', 'Add Successfully', 1);
        else $this->ajaxReturn('', 'Add Failed', 0);
    }

    Public function postSelect() {
        $database = $this->_post('database');
        $DB = D($database);
        if ($database == "Admin") {
            if ($this->_post('name')) $condition['name'] = $this->_post('name');
            if ($this->_post('info')) $condition['info'] = $this->_post('info');
        }
        if ($database == "User") {
            if ($this->_post('name')) $condition['USERNAME'] = $this->_post('name');
            if ($this->_post('type')) $condition['TYPE'] = $this->_post('type');
            if ($this->_post('email')) $condition['EMAIL'] = $this->_post('email');
            if ($this->_post('balance')) $condition['BALANCE'] = $this->_post('balance');
            if ($this->_post('phone')) $condition['PHONE'] = $this->_post('phone');
            if ($this->_post('vip')) $condition['VIP'] = $this->_post('vip');
            if ($this->_post('blacklist')) $condition['BLACKLIST'] = $this->_post('blacklist');
        }
        $result = $DB->where($condition)->select();
        if ($result) $this->ajaxReturn($result, "Select Successfully", 1);
        else $this->ajaxReturn($result, 'Select Failed', 0);
    }

    Public function postDelete() {
        $database = $this->_post('database');
        $DB = D($database);
        if ($database == "Admin") {
            if ($this->_post('name')) $condition['name'] = $this->_post('name');
            if ($this->_post('info')) $condition['info'] = $this->_post('info');
        }
        if ($database == "User") {
            if ($this->_post('name')) $condition['USERNAME'] = $this->_post('name');
            if ($this->_post('type')) $condition['TYPE'] = $this->_post('type');
            if ($this->_post('email')) $condition['EMAIL'] = $this->_post('email');
            if ($this->_post('balance')) $condition['BALANCE'] = $this->_post('balance');
            if ($this->_post('phone')) $condition['PHONE'] = $this->_post('phone');
            if ($this->_post('vip')) $condition['VIP'] = $this->_post('vip');
            if ($this->_post('blacklist')) $condition['BLACKLIST'] = $this->_post('blacklist');
        }
        $status = $DB->where($condition)->delete();
        if ($status) $this->ajaxReturn('', 'Delete Successfully', 1);
        else $this->ajaxReturn('', 'Delete Failed', 0);
    }

    Public function postSetVIP() {
        $database = $this->_post('database');
        $DB = D($database);
        if ($database == "User") {
            if ($this->_post('name')) $condition['USERNAME'] = $this->_post('name');
            if ($this->_post('type')) $condition['TYPE'] = $this->_post('type');
            if ($this->_post('email')) $condition['EMAIL'] = $this->_post('email');
            if ($this->_post('balance')) $condition['BALANCE'] = $this->_post('balance');
            if ($this->_post('phone')) $condition['PHONE'] = $this->_post('phone');
            if ($this->_post('blacklist')) $condition['BLACKLIST'] = $this->_post('blacklist');
        }
        $data['VIP'] = $this->_post('vip');
        $status = $DB->where($condition)->save($data);
        if ($status) $this->ajaxReturn('', 'Set VIP Successfully', 1);
        else $this->ajaxReturn('', 'Set VIP Failed', 0);
    }

    Public function postVRN() {
        $DB = D('Realname');
        $condition['id'] = $this->_post('id');
        $data = $DB->where($condition)->find();
        if ($data['name'] == $this->_post('name')) return $this->ajaxReturn("", "TRUE", 1);
        else return $this->ajaxReturn("", "FALSE", 0);
    }

    Public function postVC() {
        $DB = D('Card');
        $condition['id'] = $this->_post('id');
        $data = $DB->where($condition)->find();
        if ($data['name'] == $this->_post('name')) return $this->ajaxReturn("", "TRUE", 1);
        else return $this->ajaxReturn("", "FALSE", 0);
    }
}
?>