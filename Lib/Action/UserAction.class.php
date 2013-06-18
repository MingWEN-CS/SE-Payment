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
            
            //$type = $this->_post('type');

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


            $User = D("User");
            $checkData = $User->checkEmail($email, $type);
            if ($checkData != NULL){
                $this->ajaxReturn(0, $checkData, 0);
            }
            else{
            
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
                                else  $this->ajaxReturn(0,'Register failed',0);
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
                                else  $this->ajaxReturn(0,'Register failed',0);
                            }
                            else {
                                $this->ajaxReturn(0,'Register Seller failed!',0);
                            }
                        }
                    }
                    else {
                        $this->ajaxReturn(0,'Register Failed!',0);
                    }
                }
                else {
                    $this->ajaxReturn('',$User->getError(),0);
                }
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
        if (isset($_SESSION[uid])){    
        /*
        Get the user of currently logged in.
        */
        $id = $_SESSION[uid];
        $name = $_SESSION[username];
        $this->name = $name;
        
        /*
        Create a user model
        */
        $User = D('User');

        /*
        Get all the informaton that need
        to be displayed on the homepage of the user
        */
        $user = $User->where('UID ='.$id)->find();
        $this->phone = $user['PHONE'];
        $this->email = $user['EMAIL'];
        $type = $user['TYPE'];
        $this->type = $type;

        /*
        the user is classified to buyer and seller.
        Their home page is slightly different
        */

        if ($type == 0){
            $Buyer = D('Buyer');
            $buyer = $Buyer->where('UID = '.$id)->find();
            $this->credit = $buyer['CREDIT'];
            $this->isAuth = $buyer['AUTHENTICATED'];
        }

        $Address = D('Address');
        $address = $Address->findAddressById($id);
        $this->address = $address;

        $this->display();
        }
        else redirect(U('/User/login'));
    }

    public function account(){
        if (isset($_SESSION)){
        $id = $_SESSION[uid];
        $name = $_SESSION[username];

        $User = D('User');
        $user = $User->where('UID ='.$id)->find();
        $this->balance = $user['BALANCE'];

        $UserCard = D('Usercard');
        $cards = $UserCard->where('USERID ='.$id)->select();
        $number = count($cards);
        $this->cards = $cards;
        $this->number = $number;
        $this->display();
        }
        else redirect(U('/User/login'));
    }

    public function record(){
        $this->display();
    }
    
    public function setPhone(){
        if (isset($_SESSION[uid])){    
        $phone = $this->_post('phone');
        $id = $_SESSION[uid];
        if ($id){
            $User = D('User');
            if ($User->setPhone($id,$phone))
                $this->ajaxReturn(1,'Set Phone successfully!',1);
            else $this->ajaxReturn('','Set Phone Failed!',0);
        }
        else return $this->ajaxReturn('','Set Phone Failed!',0);
        }
        else redirect(U('/User/login'));
    }

    public function modifyPhone(){
        if (isset($_SESSION[uid])){    
        $phone = $this->_post('phone');
        $id = $_SESSION[uid];
        if ($id){
            $User = D('User');
            if ($User->ModifyPhone($id,$phone))
                $this->ajaxReturn(1,'Modify Phone successfully!',1);
            else $this->ajaxReturn('','Modify Phone Failed!',0);
        }
        else return $this->ajaxReturn('','Modify Phone Failed!',0);
        }
        else redirect(U('/User/login'));
    }

    public function deleteAddress($aid){
        if (isset($_SESSION[uid])){    
        $Address = D('Address');
        if ($Address->where('ADDRESSID ='.$aid)->delete()){
            $this->ajaxReturn(1,'Delete Address Successfully!',1);
        }
        else {
            $this->ajaxReturn(0,$Address->getError(),0);
        }
        }
        else redirect(U('/User/login'));
    }

    public function deleteCard($cid){
        if (isset($_SESSION[uid])){    
        $Usercard = D('Usercard');
        if ($Usercard->where('ID ='.$cid)->delete()){
            $this->ajaxReturn(1,'Delete Card Successfully!',1);
        }
        else {
            $this->ajaxReturn(0,$Usercard->getError(),0);
        }
        }
        else redirect(U('/User/login'));
    }

    public function changeLoginPwd(){
        if (isset($_SESSION[uid])){
            $id = $_SESSION[uid];
            $oldpwd = $this->_post('oldpwd');
            $pwd = $this->_post('pwd');
            $User = D('User');
            $user = $User->where('UID ='.$id)->find();
            if (md5($oldpwd)==$user['PASSWD']){
               if ($User->where('UID ='.$id)->setField('PASSWD', md5($pwd))){
                    $this->ajaxReturn(1,'Change Password Successfully',1);
                }
                else{
                    $this->ajaxReturn(0,'Change Password Failed!',0);
                } 
            }
            else $this->ajaxReturn(0,'Your password is not correct!',0);
        }
        else redirect(U('/User/login'));
    }

    public function changePaymentPwd(){
        if (isset($_SESSION[uid])){
            $id = $_SESSION[uid];
            $oldpwd = $this->_post('oldpwd');
            $pwd = $this->_post('pwd');
            
            $User = D('User');
            $user = $User->where('UID ='.$id)->find();
            if ($user['TYPE'] == 0){
                $Buyer = D('Buyer');
                $buyer = $Buyer->where('UID ='.$id)->find();
                if (md5($oldpwd) == $buyer['PASSWDPAYMENT']){

                    if ($Buyer->where('UID ='.$id)->setField('PASSWDPAYMENT',md5($pwd))){
                        $this->ajaxReturn(1,'Change Password Successfully',1);
                    }else {
                        $this->ajaxReturn(0,'Change Password Failed!',0);
                    }
                } 
                else $this->ajaxReturn(0,'Your password is not correct!',0);

            }else if ($user['TYPE'] == 1){
                $Seller = D('Seller');
                $seller = $Seller->where('UID ='.$id)->find();
                if (md5($oldpwd) == $buyer['PASSWDCONSIGN']){
                    if ($Seller->where('UID ='.$id)->setField('PASSWDCONSIGN',md5($pwd))){
                        $this->ajaxReturn(1,'Change Password Successfully',1);
                    }else {
                        $this->ajaxReturn(0,'Change Password Failed!',0);
                    }
                }
                else $this->ajaxReturn(0,'Your password is not correct!',0); 
            }
        }
        else redirect(U('/User/login'));
    }

    public function chargeMoney(){
    if (isset($_SESSION[uid])){
        $id = $_SESSION[uid];
        $cardId = $this->_post('cardId');
        $money = $this->_post('money');
        $cardPwd = $this->_post('cardPwd');

        $flag = true;
        /*
        Using the interface by Gourp5
        $Admin = D('Admin');
        $flag = $Admin->AuthCard($cardId,$cardNo);
        */
        if ($flag == false){
            $this->ajaxReturn(0,'Password for card is wrong!',0);
        }
        else {
            $User = D('User');
            if($User->where('UID = '.$id)->setInc('BALANCE',$money))
                $this->ajaxReturn(1,'Charge Successfully!',1);
            else $this->ajaxReturn(0,$User->getError(),0);
        }
    } 
    else redirect(U('/User/login'));   
    
    }

    public function addAddress(){
    if (isset($_SESSION[uid])){    
        $uid = $_SESSION[uid];
        $province = $this->_post('province');
        $city = $this->_post('city');
        $district = $this->_post('district');
        $street = $this->_post('street');

        $data = array(
            'uid' => $uid,
            'province'  => $province,
            'city' => $city,
            'district' => $district,
            'street' => $street,
        );

        $Address = D('Address');
        if ($Address->create($data)){
            $aid = $Address->add();
            if ($aid){
                $this->ajaxReturn($aid,'Add address successfully!',1);
            }
            else  $this->ajaxReturn(0,'Add address failed',0);
        }
        else {
            $this->ajaxReturn(0,$Address->getError(),0);
        }
        }
        else redirect(U('/User/login'));
    }

    public function addBankCard(){
    if (isset($_SESSION[uid])){
        $id = $_SESSION[uid];
        $cardId = $this->_post('cardNo');
        $cardPwd = $this->_post('cardPwd');

        $flag = true;
        /*
        Using the interface by Gourp5
        $Admin = D('Admin');
        $flag = $Admin->AuthCard($cardId,$cardNo);
        */

        if ($flag){
            $UserCard = D('Usercard');
            $data = array(
                'userId' => $id,
                'cardId' => $cardId,
            );
            $cards = $UserCard->where('USERID='.$id.' and CARDID ="'.$cardId.'"')->find();
            
            if (empty($cards)){
                if ($UserCard->create($data)){
                    $cid = $UserCard->add();
                    if ($cid){
                        $this->ajaxReturn($cid,'Add Card Successfully!',1);
                    }
                    else  $this->ajaxReturn(0,'Add Card failed',0);
                }
                else {
                    $this->ajaxReturn(0,$UserCard->getError(),0);
                }
            }
            else $this->ajaxReturn(0,'You have already added this card!',0);

        }

    }
    else redirect(U('User/login'));
    }

    public function authenticate(){
    if (isset($_SESSION[uid])){
        $id = $_SESSION[uid];
        $realName = $this->_post('realName');
        $idNumber = $this->_post('idNumber');

        $flag = true;
        /*
        Using the interface by group 5
        $Authenticate = D('Authenticate');
        $flag = $Authenticate->Auth($realName,$idNumber);
        */
        if ($flag){
            $Buyer = D('Buyer');
            if ($Buyer->authenticate($id)) 
                $this->ajaxReturn(1,'Authenticate successfully!',1);
            else $this->ajaxReturn('','Authenticate Failed',0);

        }
        else {
            $this->ajaxReturn('','Authenticate Failed!',0);
        }
        }
        else redirect(U('/User/login'));

    }
}

?>
