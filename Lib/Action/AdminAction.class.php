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

    Public function postAdminAdd() {
        $data['name'] = $this->_post('name');
        $data['password'] = $this->_post('password');
        $data['type'] = $this->_post('type');
        $status = D('Admin')->add($data);
        if ($status) $this->ajaxReturn('', 'Add Successfully', 1);
        else $this->ajaxReturn('', 'Add Failed', 0);
    }

    Public function postUserAdd() {
        $data['USERNAME'] = $this->_post('name');
        $data['PASSWD'] = $this->_post('password');
        $data['EMAIL'] = $this->_post('email');
        $data['PHONE'] = $this->_post('phone');
        $data['TYPE'] = $this->_post('type');
        $data['VIP'] = $this->_post('vip');
        $data['BLACKLIST'] = $this->_post('blacklist');
        $status = D('User')->add($data);
        if ($status) $this->ajaxReturn('', 'Add Successfully', 1);
        else $this->ajaxReturn('', 'Add Failed', 0);
    }

    Public function postAdminSelect() {
        if ($this->_post('name')!="") $condition['name'] = $this->_post('name');
        if ($this->_post('type')!="") $condition['type'] = $this->_post('type');
        $result = D('Admin')->where($condition)->find();
        if ($result) $this->ajaxReturn($result, "Select Successfully", 1);
        else $this->ajaxReturn($result, 'Select Failed', 0);
    }

    Public function postUserSelect() {
        if ($this->_post('name')!="") $condition['USERNAME'] = $this->_post('name');
        if ($this->_post('type')!="") $condition['TYPE'] = $this->_post('type');
        if ($this->_post('email')!="") $condition['EMAIL'] = $this->_post('email');
        if ($this->_post('balance')!="") $condition['BALANCE'] = $this->_post('balance');
        if ($this->_post('phone')!="") $condition['PHONE'] = $this->_post('phone');
        if ($this->_post('vip')!="") $condition['VIP'] = $this->_post('vip');
        if ($this->_post('blacklist')!="") $condition['BLACKLIST'] = $this->_post('blacklist');
        $result = D('User')->where($condition)->find();
        if ($result) $this->ajaxReturn($result, "Select Successfully", 1);
        else $this->ajaxReturn($result, 'Select Failed', 0);
    }

    Public function postAdminDelete() {
        if ($this->_post('name')) {
            $condition['name'] = $this->_post('name');
            $status = D('Admin')->where($condition)->delete();
            if ($status) $this->ajaxReturn('', 'Delete Successfully', 1);
            else $this->ajaxReturn('', 'Delete Failed', 0);
        }
        else {
            $this->ajaxReturn('', 'Delete Failed', 0);
        }
    }

    Public function postUserDelete() {
        if ($this->_post('name')) {
            $condition['USERNAME'] = $this->_post('name');
            $status = D('User')->where($condition)->delete();
            if ($status) $this->ajaxReturn('', 'Delete Successfully', 1);
            else $this->ajaxReturn('', 'Delete Failed', 0);
        }
        else {
            $this->ajaxReturn('', 'Delete Failed', 0);
        }
    }

    Public function postSetVIP() {
        if ($this->_post('name')) {
            $condition['USERNAME'] = $this->_post('name');
            $status = D('User')->where($condition.'AND VIP = 0')->save('VIP = 1');
            if ($status) $this->ajaxReturn('', 'Set VIP Successfully', 1);
            else {
                 $status = D('User')->where($condition.'AND VIP = 1')->save('VIP = 0');
                 $this->ajaxReturn('', 'Set VIP Successfully', 1);
            }
        }
        else {
            $this->ajaxReturn('', 'Set VIP Failed', 0);
        }
    }

    Public function postSetBL() {
        if ($this->_post('name')) {
            $condition['USERNAME'] = $this->_post('name');
            $status = D('User')->where($condition.'AND BLACKLIST = 0')->save('BLACKLIST = 1');
            if ($status) $this->ajaxReturn('', 'Set Blacklist Successfully', 1);
            else {
                 $status = D('User')->where($condition.'AND BLACKLIST = 1')->save('BLACKLIST = 0');
                 $this->ajaxReturn('', 'Set Blacklist Successfully', 1);
            }
        }
        else {
            $this->ajaxReturn('', 'Set Blacklist Failed', 0);
        }
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
        if ($data['password'] == $this->_post('password')) return $this->ajaxReturn("", "TRUE", 1);
        else return $this->ajaxReturn("", "FALSE", 0);
    }

    Public function autoSetVIP() {
        //$DB->where('BALANCE > 500 AND VIP = 0')->save('VIP = 1');
        D('User')->query('UPDATE se_user SET VIP = 1 WHERE BALANCE > 500 AND VIP = 0');
        $data = $DB->where('VIP = 1')->select();
        return $this->ajaxReturn($data, "Complete", 1);
    }

    Public function autoSetBL() {
        $Model = new Model();
        $Model->table('se_order, se_user')->query('UPDATE se_user, se_orders SET se_user.BLACKLIST = 1 where se_orders.STATE = "payed" 
            AND se_orders.ISAUDIT = "YES" AND se_order.BUYER = se_user.UID AND se_user.BLACKLIST = 0');
        $data = D('User')->where('blacklist = 1')->select();
        return $this->ajaxReturn($data, "Complete", 1);
    }
}
?>
