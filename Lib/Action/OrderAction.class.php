<?php
import("@.Util.Goods.GoodsHelper");
class OrderAction extends Action{


    private function getUserID(){

        $userid=$_SESSION['uid'];
        return $userid;
    }

    private function getUserName(){
        return $this->getUserID();
    }

    private function generatebtntype($isBuyer, $state){
        if($isBuyer){	//buyer state
            switch($state){
            case 'created': return 'pay';
            case 'payed': return 'refund';
            case 'shipping': return 'confirm_receipt';

            case 'canceled':
            case 'refunding':
            case 'refunded':
            case 'auditing':
            case 'audited':
            case 'failed':
            case 'finished': return "comment";
            default: return 'wait';
            }
        } else {	//seller state
            switch($state){
            case 'payed': return 'shipping';
            case 'refunding': return 'confirm_refund';

            case 'created':
            case 'canceled':
            case 'refunded':
            case 'auditing':
            case 'audited':
            case 'shipping':
            case 'failed':
            case 'finished': return null;
            default: return 'wait';
            }
        }
    }


    private function removeDeletedOrders($goodsList) {
        $orders=D('Orders');

        $result = array();
        for($i = 0,$j=0; $i < count($goodsList); ++$i){
            $order=$orders->findorderbyid($goodsList[$i]['OID']);
            if($order['ISDELETE'] == 'NO')
                $result[$j++] = $goodsList[$i];
        }

        return $result;
    }
    private function getusertype($uid){
        $user=D('User');
        $condition['UID']=$uid;
        $userinfo=$user->where($condition)->field('TYPE')->find();
        return $userinfo['TYPE'];
    }

    private function getshowcontent($state,$isbuyer)
    {
        if($isbuyer){

            switch($state){
            case 'created':{$content="The order has been created,pay the order to make seller send the goods";break;}
            case 'payed':{$content="waiting for the seller to ship";break;}
            case 'shipping':{$content="the seller has shiped the goods,confirm recepit if you have received the goods";break;}
            case 'refunding':{$content="waiting for the seller to confirm the refund";break;}
            case 'refunded':{$content="the refund request has been aggreed by the seller";break;}
            case 'finished':{$content="the transaction has been finished";break;}
            case 'canceled':{$content="the order has been canceled";break;}
            case 'auditing':{$content="the order is being auditing";break;}
            case 'audited':{$content="the order has been audited";break;}
            case 'failed':{$content="the transaction is failed";break;}
            default:break;
            }
        }
        else
        {
            switch($state){
            case 'created':{$content="The order has been created,waiting for the buyer to pay the order";break;}
            case 'payed':{$content="the buyer has payed the order,please ship the goods as soon as possible";break;}
            case 'shipping':{$content="waiting for the buyer to recieve the goods";break;}
            case 'refunding':{$content="the buyer request refund for some reason,if you agree on the refund,confirm to return the money back to the user";break;}
            case 'refunded':{$content="the order has been refunded";break;}
            case 'finished':{$content="the transaction has been finished";break;}
            case 'canceled':{$content="the order has been canceled";break;}
            case 'auditing':{$content="the order is being auditing";break;}
            case 'audited':{$content="the order has been audited";break;}
            case 'failed':{$content="the transaction is failed";break;}
            default:break;

            }
        }
        return $content;
    }
    public function index() {
        $this->display('showorders');
    }


    public function showorders(){

        $userID = $this->getUserID();
        if($userID===null)
        {
            $this->display();
            return;
        }
/*
get isBuyer from group 1
 */
        $isSeller=$this->getusertype($userID);
        $isBuyer=!$isSeller;
        $orders=D('Orders');
        $ordergoods=D('OrderGoods');
        $operation=D('OrderOperation');
        /*search orders whose buyer or seller is uid*/
        if(!$isSeller){
            $userorders= $orders->searchIDbyBuyerName($userID);
        } else{
            $userorders= $orders->searchIDbySellerName($userID);
        }
        $orderstate=$this->_get('state');
        $keywords=$this->_get('keywords');

        $condition['keywords']=$keywords;
        /*save the orders id*/
        for($i=0;$i<count($userorders);$i++)
        {
            if($orderstate===null||$orderstate!=null&&$orderstate==$userorders[$i]['STATE'])
                $useroid[$i]=$userorders[$i]['ID'];
        }

        $condition['userorders']=$useroid;

        /*search the order goods whose oid is in the $useroid and name like the keywords*/
        $searchResult = $ordergoods->searchbyname($condition);//搜索类似商品名称的订单，结果可能大于1

        /*delete the order whick is deleted by the user*/
        $searchResult = $this->removeDeletedOrders($searchResult);

        $orderresult=null;
        for($i=0;$i<count($searchResult);$i++)
        {
            $orderresult[$i]=$orders->findorderbyid($searchResult[$i]['OID']);
            $goodsresult=$ordergoods->searchbyid($orderresult[$i]['ID']);
            $createtime=$operation->getcreatetime($orderresult[$i]['ID']);
            $orderresult[$i]['GOODS']=$goodsresult;
            $orderresult[$i]['SIZE']=count($goodsresult);

            $state=$this->generatebtntype($isBuyer, $orderresult[$i]['STATE']);
            $orderresult[$i]['BUTTONTYPE']=$state;
            $orderresult[$i]['HREF']='./'.$state.'?oid='.$searchResult[$i]['OID'];
            $orderresult[$i]['detail']='./detail'.'?oid='.$searchResult[$i]['OID'];
            $orderresult[$i]['createtime']=$createtime;
            if($state===null)
            {
                $orderresult[$i]['HREF']='./back';	
            }
            if($state==="comment")
            {
                $orderresult[$i]['HREF']='__APP__/Purchase/comment?oid='.$searchResult[$i]['OID'];
            }

            switch($orderresult[$i]['STATE']){
            case 'created':{
                $orderresult[$i]['OTHER'] = 'cancel';
                $orderresult[$i]['OTHER_HREF'] = './cancel'.'?oid='.$searchResult[$i]['OID'];
                break;
            }

            case 'payed' :
            case 'shipping':
            case 'auditing':
            case 'wait': {
                $orderresult[$i]['OTHER'] = null;
                $orderresult[$i]['OTHER_HREF'] = './cancel'.'?oid='.$searchResult[$i]['OID'];
                break;
            }

            case 'refunding':{
                if($isBuyer){
                    $orderresult[$i]['OTHER'] = null;
                    $orderresult[$i]['OTHER_HREF'] = './cancel'.'?oid='.$searchResult[$i]['OID'];
                } else{
                    $orderresult[$i]['OTHER'] = 'refuse_refund';
                    $orderresult[$i]['OTHER_HREF'] = './refuse_refund'.'?oid='.$searchResult[$i]['OID'];
                }
                break;
            }

            default:{
                $orderresult[$i]['OTHER'] = 'delete';
                $orderresult[$i]['OTHER_HREF'] = './delete'.'?oid='.$searchResult[$i]['OID'];
            }
            }

        }
        $this->assign('myorders',$orderresult);
        $this->assign('keywords',$keywords);
        $this->display();
    }


    // buyer operation

    public function cancel(){
        $oid = $this->_get('oid');
        $userID = $this->getUserID();

        $operations = D('OrderOperation');
        $operations->addOperation($oid, "cancel", $userID);

        $orders=D('Orders');
        $orders->changeState($oid, 'canceled');
        $this->success('成功取消', U('Order/showorders'));
    }

    public function pay(){
        $oid = $this->_get('oid');

        $this->assign('oid', $oid);
        $this->display();
    }

    public function pay_authentication() {
        $oid = $this->_post('oid');
        $psw = $this->_post('password');
        $userID = $this->getUserID();

        /*get authentication from group 1*/
        $usertype=$this->getusertype($userID);
        $authen=0;
        if(!$usertype)
        {
            $buyerdb=D('Buyer');
            $buyercondition['UID']=$userID;
            $buyerinfo=$buyerdb->where($buyercondition)->find();
            if(md5($psw)===$buyerinfo['PASSWDPAYMENT'])
                $authen=1;
        }
        else{
            $sellerdb=D('Seller');
            $sellercondition['UID']=$userID;
            $buyerinfo=$sellerdb->where($sellercondition)->find();
            if(md5($psw)==$sellerinfo['PASSWDCONSIGN'])
                $authen=1;
        }

        if($authen==1){
            $operations = D('OrderOperation');/*change operation*/
            $operations->addOperation($oid, "pay", $userID);

            $orders=D('Orders');/*change order state*/
            $orders->changeState($oid, 'payed');

            $orderinfo=$orders->findorderbyid($oid);/*get order information*/
            $userdb=D('User');
            $userdb->moneyTransfer($orderinfo['BUYER'],$orderinfo['SELLER'],$orderinfo['TOTALPRICE']);/*transfer the money*/
            $this->success('付款成功', U('Order/showorders'));
        } else{
            $this->error('付款失败，密码错误', U('Order/showorders'));
        }
    }

    public function refund(){
        $oid = $this->_get('oid');
        $userID = $this->getUserID();

        $operations = D('OrderOperation');
        $operations->addOperation($oid, "refund", $userID);

        $orders=D('Orders');
        $orders->changeState($oid, 'refunding');

        $this->success('请等待退款', U('Order/showorders'));
    }

    public function confirm_receipt(){
        $oid = $this->_get('oid');
        $this->assign('oid', $oid);
        $this->display();
    }

    public function confirm_authentication() {
        $oid = $this->_post('oid');
        $psw = $this->_post('password');
        $userID = $this->getUserID();

        /*get authentication from group 1*/

        $usertype=$this->getusertype($userID);
        $authen=0;
        if(!$usertype)
        {
            $buyerdb=D('Buyer');
            $buyercondition['UID']=$userID;
            $buyerinfo=$buyerdb->where($buyercondition)->find();
            if(md5($psw)==$buyerinfo['PASSWDPAYMENT'])
                $authen=1;
        }
        else{
            $sellerdb=D('Seller');
            $sellercondition['UID']=$userID;
            $buyerinfo=$sellerdb->where($sellercondition)->find();
            if(md5($psw)==$sellerinfo['PASSWDCONSIGN'])
                $authen=1;
        }
        if($authen){
            $operations = D('OrderOperation');
            $operations->addOperation($oid, "confirm_receipt", $userID);

            $orders=D('Orders');
            $orders->changeState($oid, 'finished');

            // $orderinfo=$orders->findorderbyid($oid);/*get order information*/
            // $userdb=D('User');
            // $userdb->moneyTransfer($orderinfo['SELLER'],$orderinfo['BUYER'],$orderinfo['TOTALPRICE']);/*transfer the money*/

            $this->success('确认成功', U('Order/showorders'));
        } else{
            $this->error('确认失败，密码错误', U('Order/showorders'));
        }
    }


    // seller operation
    public function confirm_refund(){
        $oid = $this->_get('oid');
        $userID = $this->getUserID();

        $operations = D('OrderOperation');
        $operations->addOperation($oid, "confirm_refund", $userID);

        //refund operation with other group
        $orders=D('Orders');
        $orders->changeState($oid, 'refunded');

        $orderinfo=$orders->findorderbyid($oid);/*get order information*/
        $userdb=D('User');
        $userdb->moneyTransfer($orderinfo['SELLER'],$orderinfo['BUYER'],$orderinfo['TOTALPRICE']);/*transfer the money*/

        $this->success('确认退款', U('Order/showorders'));
    }


    public function refuse_refund() {
        $oid = $this->_get('oid');
        $userID = $this->getUserID();

        $operations = D('OrderOperation');
        $operations->addOperation($oid, "refuse_refund", $userID);

        $orders=D('Orders');
        $orders->changeState($oid, 'auditing');

        $this->success('等待审计', U('Order/showorders'));
    }


    public function shipping(){
        $oid = $this->_get('oid');
        $userID = $this->getUserID();

        $operations = D('OrderOperation');
        $operations->addOperation($oid, "shipping", $userID);

        $orders=D('Orders');
        $orders->changeState($oid, 'shipping');

        $this->success('确认送货', U('Order/showorders'));
    }



    //user operation
    public function delete() {
        $oid = $this->_get('oid');
        $userID = $this->getUserID();
        $operations = D('OrderOperation');
        $operations->addOperation($oid, "delete", $userID);
        $orders=D('Orders');
        $orders->delete($oid);
        $this->success('成功删除', U('Order/showorders'));
    }

    public function back(){
        redirect(U('Order/showorders'));
    }

    public function audited($oid, $auditorID) {
        // with Audit group
        $userID = $auditorID;

        $operations = D('OrderOperation');
        $operations->addOperation($oid, "audit", $userID);

        $orders=D('Orders');
        $orders->changeState($oid, 'audited');
        $orders->audited($oid);
    }

    public function createorder(){
        /*cartinfo:good id and good amount list*/
        /*data for test*/
        $cartinfo[0]['goods_id']='1';
        $cartinfo[0]['goods_count']=1;
        $cartinfo[1]['goods_id']='4';
        $cartinfo[1]['goods_count']=3;
        $cartinfo[2]['goods_id']='2';
        $cartinfo[2]['goods_count']=2;
        for($i=0;$i<count($cartinfo);$i++){
            $goodinfo=GoodsHelper::getBasicGoodsInfoOfId($cartinfo[$i]['goods_id']);
            $seller_id=$goodinfo['seller_id'];
            $goodlist['GID']=$cartinfo[$i]['goods_id'];
            $goodlist['PRICE']=$goodinfo['price'];
            $goodlist['AMOUNT']=$cartinfo[$i]['goods_count'];
            $goodlist['NAME']=$goodinfo['name'];
            $goodlist['IMGURL']=$goodinfo['image_uri'];
            $classifiedinfo[$seller_id]['goods'][count($classifiedinfo[$seller_id]['goods'])]=$goodlist;
            $classifiedinfo[$seller_id]['SELLER']=$seller_id;
        }
        $orderdb=D('Orders');
        $operation=D('OrderOperation');
        $ordergoodsdb=D('OrderGoods');
        foreach($classifiedinfo as $orderinfo){
            $neworder['SELLER']=$orderinfo['SELLER'];
            $neworder['BUYER']=$this->getUserID();
            $neworder['TOTALPRICE']=0.00;
            foreach($orderinfo['goods'] as $eachgood){
                $neworder['TOTALPRICE']+=$eachgood['PRICE']*$eachgood['AMOUNT'];
            }

            $newoid[$i]['OID']=$orderdb->insertneworder($neworder);
            $operation->addOperation($newoid[$i]['OID'],"created",$this->getUserID());
            $newoid[$i]['result']='success';
            foreach($orderinfo['goods'] as $eachgood){
                $newordergood=$eachgood;
                $newordergood['OID']=$newoid[$i]['OID'];
                $ogid=$ordergoodsdb->insertnewgood($newordergood);
                if($ogid===false)
                    $newoid[$i]['result']='fail';
                var_dump($newoid);
            }
        }
        return $newoid;
    }
    public function detail(){
        /*check if the asking order is the user's order*/
        $userid=$this->getUserID();
        if($userid===null)
        {
            $this->display("showorders");   
            return;
        }

        $isbuyer=!$this->getusertype($userid);
        $oid=$this->_get('oid');
        $Orders=D('Orders');
        $operation=D('OrderOperation');
        $orderresult=$Orders->findorderbyid($oid);
        if($isbuyer)
        {   if($orderresult['BUYER']!=$userid)
        {  $this->display("showorders");
        return;
        }}
        else{
            if($orderresult['SELLER']!=$userid)
            {     $this->display("showorders");
            return;
            }}

                $goods=D('OrderGoods');
            $goodsresult=$goods->searchbyid($oid);
            $linecount=count($goodsresult);
            $time=$operation->getoptime($oid);
            $style="width:25%";
            if($time['pay']!=null)
                $style="width:50%";
            if($time['ship']!=null)
                $style="width:75%";
            if($time['confirm']!=null)
                $style="width:100%";

            $orderstate=$orderresult['STATE'];

            $receiveaddress=D('receiveaddress');
            $addresscondition['ADDRESSID']=$orderresult['ADDRESSID'];
            $addressinfo=$receiveaddress->where($addresscondition)->find();

            $content=$this->getshowcontent($orderstate,$isbuyer);
            $this->assign('prostyle',$style);
            $this->assign('optime',$time);
            $this->assign('goods',$goodsresult);
            $this->assign('goodsize',$linecount);
            $this->assign('order',$orderresult);
            $this->assign('addressinfo',$addressinfo);
            $this->assign('content',$content);
            $this->display();
    }
}
