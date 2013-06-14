<?php

class OrderAction extends Action{
    

    public function showorders(){
        $keywords=$this->_get('keywords');
        $ordergoods=D('OrderGoods');
        $searchResult=$ordergoods->searchbyname($this);//搜索类似商品名称的订单，结果可能大于1
        $orders=D('Orders');
        for($i=0;$i<count($searchResult);$i++)
        {
            $orderresult[$i]=$orders->findorderbyid($searchResult[$i]['oid']);
         // var_dump($orderresult);
            $goodsresult=$ordergoods->searchbyid($orderresult[$i]['id']);
            $orderresult[$i]['goods']=$goodsresult;
            $orderresult[$i]['size']=count($goodsresult);
        }
        $this->assign('myorders',$orderresult);
        $this->assign('keywords',$keywords);
        $this->display();
    }
    public function createorder()
    {
        
    }

}
