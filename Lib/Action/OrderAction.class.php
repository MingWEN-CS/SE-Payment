<?php
import("@.Util.Goods.GoodsHelper");
class OrderAction extends Action{

    private function getUserID(){

/*
		//debug module
		return 1;
*/

        $userid=$_SESSION['uid'];
        if($userid===null)
        {
            $this->display();
            return;
        }

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

            case 'canceled': return null;
            case 'refunded': return null;
            case 'failed': return null;
            case 'finished': return null;
            case 'refunding':return null;
            default: return 'wait';
            }
        } else {	//seller state
			switch($state){
				case 'payed': return 'shipping';
				case 'refunding': return 'confirm_refund';
				
				case 'shipping': return null;
				case 'canceled': return null;
				case 'refunded': return null;
				case 'finished': return null;
				case 'failed': return null;
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
        $isBuyer = 1;
/*
get isBuyer from group 1
*/

        $orders=D('Orders');
        $ordergoods=D('OrderGoods');

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
            $orderresult[$i]['GOODS']=$goodsresult;
            $orderresult[$i]['SIZE']=count($goodsresult);

            $state=$this->generatebtntype($isBuyer, $orderresult[$i]['STATE']);
            $orderresult[$i]['BUTTONTYPE']=$state;
            $orderresult[$i]['HREF']='./'.$state.'?oid='.$searchResult[$i]['OID'];
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
				/*
				case 'payed' :{
					$orderresult[$i]['OTHER'] = null;
					$orderresult[$i]['OTHER_HREF'] = './cancel'.'?oid='.$searchResult[$i]['OID'];
					break;
				}
				*/

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
    public function createorder($cartinfo){
        /*cartinfo:good id and good amount list*/
        /*1、 先取出id对应的商品的属性
            2、将属性根据不同的卖家ID分类到不同的表单里
          3、计算商品的总价，插入订单，和订单商品  
         */

    
        for($i=0;$i<count($cartinfo);$i++){
            $goodinfo=GoodsHelper::getBasicGoodsInfoOfId($cartinfo[$i]['id']);
            $orderinfo[$goodinfo['seller_id']]['BUYER']=$this->getUserID();                        
        }
     
    }
}
