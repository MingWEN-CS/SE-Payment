<?php
// 本类由系统自动生成，仅供测试用途
class PurchaseAction extends Action {
    
    public function index(){
		$this->display();
    }

    public function login(){
    	$this->display("index");
    }

    public function search(){
		$keywords = $this->_get('keywords');
		$generalGoods = D('GeneralGoods');
		$searchResult = $generalGoods->getGoodsWithKeyWords($keywords);
		$this->assign('goods', $searchResult);
		$this->assign('keywords', $keywords);
		$this->display();
		// $goods = D('Goods');
		// if($goods->add()) {
		// 	$this->success("s");
		// }
		// else {
		// 	echo ($goods->getError());
		// 	echo 3;
		// }
    }
}
