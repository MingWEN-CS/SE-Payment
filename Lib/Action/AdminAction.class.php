<?php
// 本类由系统自动生成，仅供测试用途
class AdminAction extends Action {
    
    Public function login() {
        //match username and password
        if(IS_AJAX) {
            $adminname = $this->_post('adminname');
            $condition['name'] = $adminname;
            $password = $this->_post('password');
            $se_admin = D("Admin");
            $admin = $se_admin->where($condition)->find();
            if (!$admin){
                 $this->ajaxReturn('', 'Admin Does Not Exsit', 0);
            }
            else if ($password != $admin['password']){
                 $this->ajaxReturn('', 'Wrong Password', 0);
            }
            else {
                if ($admin['type'] == 0) { // if the user is admin jump to admin html
                    //set session to prevent from directly access admin or audit html
                    session('isLogin', 1);
                    $this->ajaxReturn('', 'Loading Admin System', 1);
                }
                else {//if the user is auditor jump to audit html
					session('id', $admin['id']);
                    session('name', $admin['name']);
                    $this->ajaxReturn('', 'Loading Audit System', 2);
                }
            }
        }
        else {
            $this->display();
        }
    }

    Public function index() {
        //set session 0 if leave the login page      
        if(session('isLogin')) {
            session('isLogin', 0);
            $this->display();
        }
        else //prevent from directly access to admin html
            $this->redirect('login');
    }

     //add admin
    Public function postAdminAdd() {
        $data['name'] = $this->_post('name');
        $data['password'] = $this->_post('password');
        $data['type'] = $this->_post('type');
        $status = D('Admin')->add($data);
        if ($status) $this->ajaxReturn('', 'Add Successfully', 1);
        else $this->ajaxReturn('', 'Add Failed', 0);
    }

    //add user
    Public function postUserAdd() {
        $data['USERNAME'] = $this->_post('name');
        $data['PASSWD'] = $this->_post('password');
        $data['EMAIL'] = $this->_post('email');
        $data['PHONE'] = $this->_post('phone');
        $data['TYPE'] = $this->_post('type');
        $status = D('User')->add($data);
        if ($status) $this->ajaxReturn('', 'Add Successfully', 1);
        else $this->ajaxReturn('', 'Add Failed', 0);
    }

    //select admin information
    Public function postAdminSelect() {
        if ($this->_post('name')!="") $condition['name'] = $this->_post('name');
        if ($this->_post('type')!="") $condition['type'] = $this->_post('type');
        $result = D('Admin')->where($condition)->select();
        if ($result) $this->ajaxReturn($result, "Select Successfully", 1);
        else $this->ajaxReturn($result, 'Select Failed', 0);
    }

    //select users by condition as posted
    Public function postUserSelect() {
        //add condition in increasing way aka +AND CONDITION[i]
        if ($this->_post('name')!="") $condition['USERNAME'] = $this->_post('name');
        if ($this->_post('type')!="") $condition['TYPE'] = $this->_post('type');
        if ($this->_post('email')!="") $condition['EMAIL'] = $this->_post('email');
        if ($this->_post('balance')!="") $condition['BALANCE'] = $this->_post('balance');
        if ($this->_post('phone')!="") $condition['PHONE'] = $this->_post('phone');
        if ($this->_post('vip')!="") $condition['VIP'] = $this->_post('vip');
        if ($this->_post('blacklist')!="") $condition['BLACKLIST'] = $this->_post('blacklist');
        $result = D('User')->where($condition)->select();
        if ($result) $this->ajaxReturn($result, "Select Successfully", 1);
        else $this->ajaxReturn($result, 'Select Failed', 0);
    }

    //delete an admin who has the same name as posted
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

    //delete a user who has the same name as posted
    Public function postUserDelete() {
        if ($this->_post('name')) {
            $condition['USERNAME'] = $this->_post('name');
            $user = D('User')->where($condition)->find();
            $status = D('Buyer')->where('UID ='.$user['UID'])->delete();
            $status = D('User')->where($condition)->delete();
            if ($status) $this->ajaxReturn('', 'Delete Successfully', 1);
            else $this->ajaxReturn('', 'Delete Failed', 0);
        }
        else {
            $this->ajaxReturn('', 'Delete Failed', 0);
        }
    }

    // //set a user to be a vip return 1 if setted 0 otherwise
    // Public function postSetVIP() {
    //     if ($this->_post('name')) {
    //         $condition['USERNAME'] = $this->_post('name');
    //         $status = D('User')->where($condition.'AND VIP = 0')->save('VIP = 1');
    //         if ($status) $this->ajaxReturn('', 'Set VIP Successfully', 1);
    //         else {
    //              $status = D('User')->where($condition.'AND VIP = 1')->save('VIP = 0');
    //              $this->ajaxReturn('', 'Set VIP Successfully', 1);
    //         }
    //     }
    //     else {
    //         $this->ajaxReturn('', 'Set VIP Failed', 0);
    //     }
    // }

    // //set a user to be in blacklist return 1 if setted 0 otherwise
    // Public function postSetBL() {
    //     if ($this->_post('name')) {
    //         $condition['USERNAME'] = $this->_post('name');
    //         $status = D('User')->where($condition.'AND BLACKLIST = 0')->save('BLACKLIST = 1');
    //         if ($status) $this->ajaxReturn('', 'Set Blacklist Successfully', 1);
    //         else {
    //              $status = D('User')->where($condition.'AND BLACKLIST = 1')->save('BLACKLIST = 0');
    //              $this->ajaxReturn('', 'Set Blacklist Successfully', 1);
    //         }
    //     }
    //     else {
    //         $this->ajaxReturn('', 'Set Blacklist Failed', 0);
    //     }
    // }

    //match realname return 1 if the name and id matched 0 if not
    Public function postVRN() {
        $DB = D('Realname');
        $condition['id'] = $this->_post('id');
        $data = $DB->where($condition)->find();
        if ($data['name'] == $this->_post('name')) return $this->ajaxReturn("", "TRUE", 1);
        else return $this->ajaxReturn("", "FALSE", 0);
    }

    //match card infomation return 1 if id and password matched 0 if not
    Public function postVC() {
        $DB = D('Card');
        $condition['id'] = $this->_post('id');
        $data = $DB->where($condition)->find();
        if ($data['password'] == $this->_post('password')) return $this->ajaxReturn("", "TRUE", 1);
        else return $this->ajaxReturn("", "FALSE", 0);
    }

    Public function autoSetVIP() {
        //set a user to vip if his balance has more than 500 RMB
        D('User')->query('UPDATE se_user SET VIP = 1 WHERE BALANCE > 500 AND VIP = 0');
        $data = D('User')->where('VIP = 1')->select();
        //return 1 if the process is completed
        return $this->ajaxReturn($data, "Complete", 1);
    }

    Public function autoSetBL() {
        $Model = new Model();
        //if an order of a buyer isaudit and the state is payed then he should be in blacklist
        $Model->table('se_orders, se_user')->query('UPDATE se_user, se_orders SET se_user.BLACKLIST = 1 where se_orders.STATE = "payed" 
            AND se_orders.ISAUDIT = "YES" AND se_orders.BUYER = se_user.UID AND se_user.BLACKLIST = 0');
        //if an order of a seller isaudit and the state is refunded then he should be in blacklist
        $Model->table('se_orders, se_user')->query('UPDATE se_user, se_orders SET se_user.BLACKLIST = 1 where se_orders.STATE = "refunded" 
            AND se_orders.ISAUDIT = "YES" AND se_orders.SELLER = se_user.UID AND se_user.BLACKLIST = 0');
        $data = D('User')->where('blacklist = 1')->select();
        //return all user data who is in blacklist
        return $this->ajaxReturn($data, "Complete", 1);
    }

    //get why the user is in the blacklist aka the invalid order records of a user
    Public function getBLReason() {
        $name['USERNAME'] = $this->_post('name');
        //select user by username.
        $user = D('User')->where($name)->find();
        //check the user type: buyer for 0 seller for 1
        if($user['TYPE'] == 0) {
            $condition['BUYER'] = $user['UID'];
            $condition['STATE'] = "payed";
        }
        else {
            $condition['SELLER'] = $user['UID'];
            $condition['STATE'] = "refunded";
        }
        $condition['ISAUDIT'] = "YES";
        //select user invalid order record from database;
        $data = D('Orders')->where($condition)->select();
        //return 1 for nonempty selection
        if($data)
            return $this->ajaxReturn($data, '', 1);
        else
            return $this->ajaxReturn('', '', 0);
    }

    //add a blacklist appeal reason for specific user
    Public function addBLAppeal() {
        $data['name'] = $this->_post('name');
        $data['reason'] = $this->_post('reason');
        $isExist = D('Blacklistappeal')->where('name ='.$data['name'])->find();
        if($isExist) { //if there is already an appeal reason for the user, then update the reason with new one
            D('Blacklistappeal')->where('name ='.$data['name'])->save('reason='.$data['reason']);
            //return 0 for updated
            return $this->ajaxReturn('', 'Update Success!', 0);
        }
        else { //else add an appeal reason for the user into blacklistappeal database
            $state = D('Blacklistappeal')->add($data);
            //return 1 for added
            return $this->ajaxReturn('', 'Add Success!', 1);
        }
    }

    //get a user's blacklist appeal reason
    Public function getBLAppeal() {
        //select appeal for the user
        $condition['name'] = $this->_post('name');
        $data = D('Blacklistappeal')->where($condition)->find();
        if ($data)
            return $this->ajaxReturn($data, '', 1);//return data and 1 if there is appeal for the user
        else
            return $this->ajaxReturn('N/A', '', 0);//return N/A and 0 if not
    }

    Public function postSelectVIP(){
        $Model = new Model();
        $data = $Model->table('se_user, se_buyer')->query('SELECT * FROM se_user, se_buyer
            WHERE se_user.UID = se_buyer.UID AND se_buyer.VIP = 1');
        if ($data) $this->ajaxReturn($data, "Select VIP Successfully", 1);
        else $this->ajaxReturn($data, 'Select VIP Failed', 0);
    }

    Public function postDeleteVIP() {
        $condition['USERNAME'] = $this->_post('name');
        $user = D('User')->where($condition)->find();
        var_dump($user);
        $status = D('Buyer')->where('UID='.$user['UID'])->setField('VIP', 0);
        if ($status) $this->ajaxReturn('', 'Delete VIP Successfully', 1);
        else $this->ajaxReturn('', 'Delete VIP Failed', 0);
    }

    Public function postDeleteBL() {
        $condition['USERNAME'] = $this->_post('name');
        $status = D('User')->where($condition)->setField('BLACKLIST', 0);
        if ($status) $this->ajaxReturn('', 'Delete Blacklist Successfully', 1);
        else $this->ajaxReturn('', 'Delete Blacklist Failed', 0);
    }
}
?>
