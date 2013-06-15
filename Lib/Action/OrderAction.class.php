<?php

class OrderAction extends Action{
	
	private function getUserName(){
	
		/*
		//debug module
		return 'dniw';
		*/
		
		$username=$_SESSION['username'];
		if($username===null)
        {
           $this->display();
            return;
        }
		
		return username;
	}
	
	private function generatebtntype($isBuyer, $state){
		if($isBuyer){
			switch($state){
				case 'created': return 'pay';
				case 'payed': return 'refund';
				case 'shipping': return 'confirm_receipt';
				
				case 'canceled': return 'canceled';
				case 'refunded': return 'refunded';
				case 'failed': return 'failed';
				case 'succeed': return 'succeed';
				
				default: return 'wait';
			}
		} else {
			switch($state){
				case 'payed': return 'shipping';
				case 'refunding': return 'confirm_refund';
				
				case 'canceled': return 'canceled';
				case 'refunded': return 'refunded';
				case 'failed': return 'failed';
				case 'succeed': return 'succeed';
				default: return 'wait';
			}
		}
    }
	
	private function removeDeletedOrders($orderList) {
		$orders=D('Orders');
		
		$result = array();
		for($i = 0; $i < count($orderList); ++$i){
			$order=$orders->findorderbyid($orderList[$i]);
			if($order['isdelete'] == 'NO')
				$result[] = $orderList[$i];
		}
		
		return $result;
	}
	
	
	public function index() {
		$this->display('showorders');
	}
	
    public function showorders(){
	
		$username = $this->getUserName();
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
            $useroid[$i]=$userorders[$i]['id'];
			
        $condition['userorders']=$useroid;
        $searchResult = $ordergoods->getOrderListbyName($condition);//搜索类似商品名称的订单，结果可能大于1
		$searchResult = $this->removeDeletedOrders($searchResult);
		
        $orderresult=null;
        for($i=0;$i<count($searchResult);$i++)
        {
            $orderresult[$i]=$orders->findorderbyid($searchResult[$i]);
            $goodsresult=$ordergoods->searchbyid($orderresult[$i]['id']);
            $orderresult[$i]['goods']=$goodsresult;
            $orderresult[$i]['size']=count($goodsresult);
	
            $state=$this->generatebtntype($isBuyer, $orderresult[$i]['state']);
			$orderresult[$i]['buttontype']=$state;
			$orderresult[$i]['href']='./'.$state.'?oid='.$searchResult[$i];
            
			switch($state){
				case 'confirm_refund':{ $orderresult[$i]['buttontype']='confirm refund'; break; }
				case 'confirm_receipt':{ $orderresult[$i]['buttontype']='confirm receipt'; break; }
				
				case 'canceled':
				case 'refunded':
				case 'failed':
				case 'succeed':
				case 'wait':{
					$orderresult[$i]['href']='./back';
					break;
				}
				default:;
			}
			
			switch($orderresult[$i]['state']){
				case 'created':{
					$orderresult[$i]['other'] = 'cancel';
					$orderresult[$i]['other_href'] = './cancel'.'?oid='.$searchResult[$i];
					break;
				}
				case 'canceled':
				case 'refunded':
				case 'failed':
				case 'succeed':
				case 'wait':{
					$orderresult[$i]['other'] = 'delete';
					$orderresult[$i]['other_href'] = './delete'.'?oid='.$searchResult[$i];
					break;
				}
				default:{
					$orderresult[$i]['other'] = '';
					$orderresult[$i]['other_href'] = './back';
				}
			}

        }
        $this->assign('myorders',$orderresult);
        $this->assign('keywords',$keywords);
        $this->display();
    }

	
	// buyer operation
	
	public function cancel() {
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
			$orders->changeState($oid, 'succeed');
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
}
