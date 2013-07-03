<?php
import("@.Util.Goods.SourcePlace");

// 本类由系统自动生成，仅供测试用途
class UserAction extends Action {
    
    public function index(){
        if (isset($_SESSION[username])) redirect(U('/User/home'));
        else redirect(U('/User/login'));
    }

    /*
    Deal with login
    Get the post name and post email
    */
    public function login(){
    	if (IS_POST){
            $name = $this->_post('name');
            $pwd = $this->_post('pwd');
            
            $type = $this->_post('type');

            $User = D("User");
            
            //find the user by name
            $user = $User->findUserByName($name);
            if (!$user){
                $this->ajaxReturn('','User do not exsit!',0);
            }
            /*
            validate the password of the user
            if they do not mathc, login failed
            return error info
            */
            else if (md5($pwd) != $user[PASSWD]){
                $this->ajaxReturn('','Password incorrect',0);
            }
            else if ($user['BLACKLIST'] == 1){
                $this->ajaxReturn('','You are in the black list',2);
               // $this->redirect('/Admin/appeal');
            }
            else {
            //login successful and create session    
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

    /*
        Register 
    */
    public function register(){
        if (IS_POST){
            //get all the register information
            $pwd =  $this->_post('pwd');
            $name = $this->_post('name');
            $email = $this->_post('email');
            $pwd2 = $this->_post('pwd2');
            $phone = $this->_post('phone');
            $type = $this->_post('type');
            //$data = {'name':$name,'pwd':$pwd,'email':$email};
            //create the array data

            $data = array(
                'name' => $name,
                'pwd'  => $pwd,
                'email' => $email,
                'type' => $type,
                'phone' => $phone,
            );

            /*
             
            */

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
                                    session('type',0);

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
                                    session('type',1);

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
                    $this->ajaxReturn(0,'Register a Failed!',0);
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
        $this->realName = $user['REALNAME'];
        $this->idNumber = $user['IDENTITY'];
        /*
        the user is classified to buyer and seller.
        Their home page is slightly different
        */

        if ($type == 0){
            $Buyer = D('Buyer');
            $buyer = $Buyer->where('UID = '.$id)->find();
            $this->credit = $buyer['CREDIT'];
        }

        $this->isAuth = $user['AUTHENTICATED'];
        $Address = D('Address');
        

        $address = $Address->findAddressById($id);
        //print_r($address);
        $this->address = $address;
        $pp = GeneralGoodsModel::getSourcePlaceObjectsArray();
        //print_r($pp);
        $this->province = $pp;
        //$this->assign('province', GeneralGoodsModel::getSourcePlaceObjectsArrayWithHead());
        $this->display();
        }
        else redirect(U('/User/login'));
    }

    public function account(){
        if (isset($_SESSION[uid])){
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
        if (isset($_SESSION[uid])){
            $id = $_SESSION[uid];
                    /*
            Create a user model
            */
            $User = D('User');

            /*
            Get all the informaton that need
            to be displayed on the homepage of the user
            */
            $user = $User->where('UID ='.$id)->find();

            $type = $user['TYPE'];

            $year = $this->_get("year");
            $month = $this->_get("month");
            $model = new Model();
            /*
                the situation of income and outcome 
                is reversed

                we should do the operation individually

            */
            if ($type == 0){
                $t_income = $model->table('se_orders,se_order_operation')->where(
                'se_orders.ID = se_order_operation.OID and 
                 (se_orders.STATE = "refunded") and 
                 se_order_operation.OPERATION = "refund" and se_orders.BUYER = '.$id)->select();

                $t_outcome = $model->table('se_orders,se_order_operation')->where(
                'se_orders.ID = se_order_operation.OID and 
                 (se_orders.STATE = "finished" or se_orders.STATE = "payed") and 
                 se_order_operation.OPERATION = "pay" and se_orders.BUYER = '.$id)->select();


                $income = array();
                $outcome = array();

                foreach ($t_income as $value){

                    $time = $value['TIME'];
                    $yyear = (int)substr($time,0,4);
                    $mmongth = (int)substr($time,6,2);

                    $User = D('USER');
                    $user = $User->where('UID ='.$value['SELLER'])->find();

                    if ($year == $yyear && $month == $mmongth){
                        $payment = array(
                        'payto' => $user['USERNAME'],
                        'money'  => $value['TOTALPRICE'],
                        'time' => $time,
                        );
                        array_push($income,$payment);
                    }
                }

                foreach ($t_outcome as $value) {
                    # code...
                    $time = $value['TIME'];
                    $yyear = (int)substr($time,0,4);
                    $mmongth = (int)substr($time,6,2);

                    $User = D('USER');
                    $user = $User->where('UID ='.$value['SELLER'])->find();

                    if ($year == $yyear && $month == $mmongth){
                        $payment = array(
                        'payto' => $user['USERNAME'],
                        'money'  => $value['TOTALPRICE'],
                        'time' => $time,
                        );
                        array_push($outcome,$payment);
                    }
                }

            }else if ($type == 1){
                $t_income = $model->table('se_orders,se_order_operation')->where(
                'se_orders.ID = se_order_operation.OID and 
                 (se_orders.STATE = "finished" or se_orders.STATE = "pay") and 
                 se_order_operation.OPERATION = "pay" and se_orders.SELLER = '.$id)->select();

                $t_outcome = $model->table('se_orders,se_order_operation')->where(
                'se_orders.ID = se_order_operation.OID and 
                 se_orders.STATE = "refunded" and
                 se_order_operation.OPERATION = "refund" and se_orders.SELLER = '.$id)->select();
            
                $income = array();
                $outcome = array();

                foreach ($t_income as $value){

                    $time = $value['TIME'];
                    $yyear = (int)substr($time,0,4);
                    $mmongth = (int)substr($time,6,2);

                    $User = D('USER');
                    $user = $User->where('UID ='.$value['BUYER'])->find();

                    if ($year == $yyear && $month == $mmongth){
                        $payment = array(
                        'payto' => $user['USERNAME'],
                        'money'  => $value['TOTALPRICE'],
                        'time' => $time,
                        );
                        array_push($income,$payment);
                    }
                }

                foreach ($t_outcome as $value) {
                    # code...
                    $time = $value['TIME'];
                    $yyear = (int)substr($time,0,4);
                    $mmongth = (int)substr($time,6,2);

                    $User = D('USER');
                    $user = $User->where('UID ='.$value['BUYER'])->find();

                    if ($year == $yyear && $month == $mmongth){
                        $payment = array(
                        'payto' => $user['USERNAME'],
                        'money'  => $value['TOTALPRICE'],
                        'time' => $time,
                        );
                        array_push($outcome,$payment);
                    }
                }
            }
            
            /*
            $income = $model->table('se_orders,se_order_operation')->where(
                'se_orders.ID = se_order_operation.OID and 
                 se_order_operation.OPERATION = "created" and se_orders.BUYER = '.$id)->select();
            */
            
            $this->income = $income;
            $this->outcome = $outcome;
            $this->display();
        }
        else redirect(U('/User/login'));
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


    /*
        Change the  Login password of
        the user
    */
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

    /*
        Change the payment password of 
        the user.
    */
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
        $Card = D('Card');
        $flag = $Card->verify($cardId,md5($cardPwd));

        if ($flag == false){
            $this->ajaxReturn(0,'Password for card is wrong!',0);
        }
        else {
            $User = D('User');
            $user = $User->where('UID = '.$id)->find();
            $before = $user['BALANCE'];
            if ($before + $money > 999999999.99){
                $this->ajaxReturn(0,'Our system can not support that much money!',0);
            }
            else if($User->where('UID = '.$id)->setInc('BALANCE',$money))
                 $this->ajaxReturn(1,'Charge Successfully!',1);
            else $this->ajaxReturn(0,$User->getError(),0);
        }
    } 
    else redirect(U('/User/login'));   
    
    }

    /*
     Add the address of the user
    */
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
        $Card = D('Card');
        $flag = $Card->verify($cardId,md5($cardPwd));
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
    else $this->ajaxReturn(0,'Card Authentication failed',0);

    }
    else redirect(U('User/login'));
    }

    public function modifyOther(){
    if (isset($_SESSION[uid])){

        $id = $_SESSION[uid];
        $email = $this->_post('email');
        $realName = $this->_post('realName');
        $idNumber = $this->_post('idNumber');
        /*
        if ($email == NULL || $realName == NULL || $idNumber == NULL){
            $this->ajaxReturn(0,'informaton is not complete!',0);
        }
        */
        $flag = true;
        $msg = "";
        $User = D('User');
        $user = $User->where('UID ='.$id)->find();
        $pattern = "/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";
        if ($email != NULL){
            if (preg_match($pattern,$email)){
            if (!$User->where('UID ='.$id)->setField('EMAIL',$email)){
                $flag = false;
                $msg = "email modify failed!";
            }
            else $msg = $msg .'Email Modify Successfully!';
            }
            else {
                $flag = false;
                $msg = "Email format error!";
            }
        }

        if ($realName != NULL && $idNumber != NULL){
            $realNameAuth =true;
            /*
            Using the interface by group 5
            $Authenticate = D('Authenticate');
            $flag = $Authenticate->Auth($realName,$idNumber);
            */
            $RealName = D('Realname');
            $realNameAuth = $RealName->verify($idNumber,$realName);

            if ($realNameAuth){
                if ($User->authenticate($id,$realName,$idNumber)){
                    $msg = $msg .' Real Name informaton modify Successfully!';
                }
                else{
                       $flag = false;
                       $msg = $msg .'Real Name informaton modify Failed!'; 
                }
                
            }
            else {
                $this->ajaxReturn('','Authenticate Failed!',0);
            }
        }

        if ($flag){
            $this->ajaxReturn(1,$msg,1);

        }else $this->ajaxReturn(0,$msg,0);
    }
    else redirect(U('/User/login'));
        
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
        $RealName = D('Realname');
        $flag = $RealName->verify($idNumber,$realName);

        if ($flag){
            $User = D('User');
            if ($User->authenticate($id,$realName,$idNumber))

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
