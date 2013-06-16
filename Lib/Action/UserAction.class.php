<?php
// 本类由系统自动生成，仅供测试用途
class UserAction extends Action {
    
    public function index(){
        if (isset($_SESSION[username])) $this->display("home");
        else redirect(U('/User/login'));
    }


    public function login(){
    	if (IS_POST){
            $name = $this->_post('name');
            $pwd = $this->_post('pwd');
            $User = D("User");
            $user = $User->findUserByName($name);
            if (!$user){
                $this->ajaxReturn('','User do not exsit!',0);
            }
            else if (md5($pwd) != $user[PASSWD]){
                $this->ajaxReturn('','Password incorrect',0);
            }
            else {
                session('uid',$user[UID]);
                session('username',$user[USERNAME]);
				session('type',$user[TYPE]);
                $this->ajaxReturn('', 'Login successfully!' ,1);
            }
        }   
        else{
            $this->display();
        }
    }


    public function register(){
        if (IS_POST){
            $pwd =  $this->_post('pwd');
            $name = $this->_post('name');
            $email = $this->_post('email');
            $pwd2 = $this->_post('pwd2');
            $phone = $this->_post('phone');
            $type = $this->_post('type');
            //$data = {'name':$name,'pwd':$pwd,'email':$email};
            $data = array(
                'name' => $name,
                'pwd'  => $pwd,
                'email' => $email,
                'type' => $type,
                'phone' => $phone,
            );
            print_r($data);
            $User = D("User");
            if ($User->create($data)){
                $uid = $User->add();
                if ($uid){
                    if ($type == 0){
                        $Buyer = D("Buyer");
                        $data = array('uid' => $uid,
                                    'pwd2' => $pwd2);
                        if ($Buyer->create($data)){
                            $bid = $Buyer->add();
                            if ($bid){
                                session('uid',$uid);
                                session('username',$name);
                                $this->ajaxReturn($bid,'Register successfully!',1);
                            }
                            else  $this->ajaxReturn(0,'Register c failed',0);
                        }
                        else {
                            $this->ajaxReturn(0,'Register Buyer failed!',0);
                        }
                    }
                    else if ($type == 1){
                        $Seller = D("Seller");
                        $data = array('uid' => $uid,
                                    'pwd2' => $pwd2);
                        if ($Seller->create($data)){
                            $sid = $Seller->add();
                            if ($sid){
                                session('uid',$uid);
                                session('username',$name);
                                $this->ajaxReturn($sid,'Register successfully!',1);
                            }
                            else  $this->ajaxReturn(0,'Register b failed',0);
                        }
                        else {
                            $this->ajaxReturn(0,'Register Seller failed!',0);
                        }
                    }
                }
                else {
                    $this->ajaxReturn(0,'Register a Failed!',0);
                }
            }
            else {
                $this->ajaxReturn('',$User->getError(),0);
            }
        }
        else{
            $this->display();
        }
    }

    public function logout(){
        session('[destroy]');
        redirect(U('/User/login'));
    }

    public function home(){
        $this->display();
    }

    public function account(){
        $this->display();
    }

    public function record(){
        $this->display();
    }
    
}

?>
