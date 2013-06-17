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

    Public function test() {
        $this->display();
        $DB = D('Admin');
        //insert
        $data['name'] = "ddl";
        $data['password'] = '123';
        $data['info'] = "test";
        $DB->add($data);
        //select
        $condition['name'] = "root";
        $result = $DB->where($condition)->select();
        var_dump($result);
        //delete
        $condition['name'] = "ddl";
        $DB->where($condition)->delete();
    }

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
        $state = $DB->add($data);
        if ($state) $this->ajaxReturn('', 'Add Successfully', 1);
        else $this->ajaxReturn('', 'Add Failed', 0);
    }

    Public function postSelect() {
        $database = $this->_post('database');
        $DB = D($database);
        if ($database == "Admin") {
            if ($name) $condition['name'] = $name;
            if ($info) $condition['info'] = $info;
        }
        if ($database == "User") {
            if ($name) $condition['USERNAME'] = $name;
            if ($type) $condition['TYPE'] = $type;
            if ($email) $condition['EMAIL'] = $email;
            if ($balance) $condition['BALANCE'] = $balance;
            if ($phone) $condition['PHONE'] = $phone;
            if ($vip) $condition['VIP'] = $vip;
            if ($blacklist) $condition['BLACKLIST'] = $blacklist;
        }
        $result = $DB->where($condition)->select();
        if ($result) $this->ajaxReturn('', $result, 1);
        else $this->ajaxReturn('', 'No Data', 0);
    }

    Public function postDelete() {
        $database = $this->_post('database');
        $DB = D($database);
        if ($database == "Admin") {
            if ($name) $condition['name'] = $name;
            if ($info) $condition['info'] = $info;
        }
        if ($database == "User") {
            if ($name) $condition['USERNAME'] = $name;
            if ($type) $condition['TYPE'] = $type;
            if ($email) $condition['EMAIL'] = $email;
            if ($balance) $condition['BALANCE'] = $balance;
            if ($phone) $condition['PHONE'] = $phone;
            if ($vip) $condition['VIP'] = $vip;
            if ($blacklist) $condition['BLACKLIST'] = $blacklist;
        }
        $DB->where($condition)->delete();
        if ($state) $this->ajaxReturn('', 'Delete Successfully', 1);
        else $this->ajaxReturn('', 'Delete Failed', 0);
    }
}
?>