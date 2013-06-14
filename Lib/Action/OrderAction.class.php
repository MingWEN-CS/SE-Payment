<?php

class OrderAction extends Action{
    
 private function generatebtntype($state)
    {
        switch($state){
        case 'created': return 'pay';
        case 'payed': return 'refund';
        case 'shipping': return 'confrim receipt';
        default: return null;
        }
    }
    public function showorders(){
        $username=$_SESSION['username'];
        if($username===null)
        {   
           $this->display();
            return;
        }
        
        $orders=D('Orders');
        $ordergoods=D('OrderGoods');
        $userorders= $orders->searchidbyname($username);
        $keywords=$this->_get('keywords');
        $condition['keywords']=$keywords;
        for($i=0;$i<count($userorders);$i++)
            $useroid[$i]=$userorders[$i]['id'];
        $condition['userorders']=$useroid;
        $searchResult=$ordergoods->searchbyname($condition);//搜索类似商品名称的订单，结果可能大于1
        $orderresult=null;
        for($i=0;$i<count($searchResult);$i++)
        {
            $orderresult[$i]=$orders->findorderbyid($searchResult[$i]['oid']);
            $goodsresult=$ordergoods->searchbyid($orderresult[$i]['id']);
            $orderresult[$i]['goods']=$goodsresult;
            $orderresult[$i]['size']=count($goodsresult);
            $state=$this->generatebtntype($orderresult[$i]['state']);
                $orderresult[$i]['buttontype']=$state;
            $orderresult[$i]['href']='/index.php/Order/'.$state;
       //     var_dump($orderresult[$i]['href']);
        }
        $this->assign('myorders',$orderresult);
        $this->assign('keywords',$keywords);
        $this->display();

    }

    public function pay(){}
    public function refund(){}
}
