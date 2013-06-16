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
            case 'finished': return null;
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


    public function index() {
        $this->display('showorders');
    }


    public function showorders(){

        $username = $this->getUserID();
        if($username===null)
        {
            $this->display();
            return;
        }
        $isBuyer = 1;
/*
get isBuyer from group 1
*/

        $orders=D('Orders');
        $ordergoods=D('OrderGoods');
        $operation=D('OrderOperation');
        if($isBuyer){
            $userorders= $orders->searchIDbyBuyerName($username);
        } else{
            $userorders= $orders->searchIDbySellerName($username);
        }

        $keywords=$this->_get('keywords');
        $condition['keywords']=$keywords;
        for($i=0;$i<count($userorders);$i++)
            $useroid[$i]=$userorders[$i]['ID'];

        $condition['userorders']=$useroid;
        $searchResult = $ordergoods->searchbyname($condition);//搜索类似商品名称的订单，结果可能大于1
        $searchResult = $this->removeDeletedOrders($searchResult);
        //var_dump($searchRe);
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


            switch($orderresult[$i]['STATE']){
            case 'created':{
                $orderresult[$i]['OTHER'] = 'cancel';
                $orderresult[$i]['OTHER_HREF'] = './cancel'.'?oid='.$searchResult[$i]['OID'];
                break;
            }

            case 'payed' :{
                $orderresult[$i]['OTHER'] = null;
                $orderresult[$i]['OTHER_HREF'] = './cancel'.'?oid='.$searchResult[$i]['OID'];
                break;
            }
            case 'shipping':{
                $orderresult[$i]['OTHER'] = null;
                $orderresult[$i]['OTHER_HREF'] = './cancel'.'?oid='.$searchResult[$i]['OID'];
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
        $username = $this->getUserName();

        $operations = D('OrderOperation');
        $operations->addOperation($oid, "cancel", $username);

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
        $username = $this->getUserName();

        /*get authentication from group 1*/
        if(1){
            $operations = D('OrderOperation');
            $operations->addOperation($oid, "pay", $username);

            $orders=D('Orders');
            $orders->changeState($oid, 'payed');
            $this->success('付款成功', U('Order/showorders'));
        } else{
            $this->error('付款失败，密码错误', U('Order/showorders'));
        }
    }

    public function refund(){
        $oid = $this->_get('oid');
        $username = $this->getUserName();

        $operations = D('OrderOperation');
        $operations->addOperation($oid, "refund", $username);

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
        $username = $this->getUserName();

        /*get authentication from group 1*/
        if(1){
            $operations = D('OrderOperation');
            $operations->addOperation($oid, "confirm_receipt", $username);
            $orders=D('Orders');
            $orders->changeState($oid, 'finished');
            $this->success('确认成功', U('Order/showorders'));
        } else{
            $this->error('确认失败，密码错误', U('Order/showorders'));
        }
    }


    // seller operation
    public function confirm_refund(){
        $oid = $this->_get('oid');
        $username = $this->getUserName();

        $operations = D('OrderOperation');
        $operations->addOperation($oid, "confirm_refund", $username);

        //refund operation with other group
        $orders=D('Orders');
        $orders->changeState($oid, 'refunded');


        $this->success('确认退款', U('Order/showorders'));
    }


public function refuse_refund() {
$oid = $this->_get('oid');
        $username = $this->getUserName();

        $operations = D('OrderOperation');
        $operations->addOperation($oid, "refuse_refund", $username);

        $orders=D('Orders');
        $orders->changeState($oid, 'auditing');

        $this->success('等待审计', U('Order/showorders'));
}


    public function shipping(){
        $oid = $this->_get('oid');
        $username = $this->getUserName();

        $operations = D('OrderOperation');
        $operations->addOperation($oid, "shipping", $username);

        $orders=D('Orders');
        $orders->changeState($oid, 'shipping');

        $this->success('确认送货', U('Order/showorders'));
    }



    //user operation
    public function delete() {
        $oid = $this->_get('oid');
        $username = $this->getUserName();
        $operations = D('OrderOperation');
        $operations->addOperation($oid, "delete", $username);
        $orders=D('Orders');
        $orders->delete($oid);
        $this->success('成功删除', U('Order/showorders'));
    }

    public function back(){
        redirect(U('Order/showorders'));
    }

public function audited($oid, $auditorName) {
// with Audit group
        $username = $auditorName;

        $operations = D('OrderOperation');
        $operations->addOperation($oid, "audit", $username);

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
        foreach($classifiedinfo as $orderinfo){
            $neworder['SELLER']=$orderinfo['SELLER'];
            $neworder['BUYER']=$this->getUserID();
            $neworder['TOTALPRICE']=0.00;
            foreach($orderinfo['goods'] as $eachgood){
                $neworder['TOTALPRICE']+=$eachgood['PRICE']*$eachgood['AMOUNT'];
            }
            $orderdb=D('Orders');
            $operation=D('OrderOperation');

            $newoid[$i]['OID']=$orderdb->insertneworder($neworder);
            $operation->addOperation($newoid[$i]['OID'],"created",$this->getUserID());

            $ordergoodsdb=D('OrderGoods');
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
}
