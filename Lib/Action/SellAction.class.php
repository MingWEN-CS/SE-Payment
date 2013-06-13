<?php
import("@.Util.Goods.SourcePlace");

class SellAction extends Action {
	
    public function index(){
		if (!isset($_SESSION['uid'])) {
			$this->display('redir');
		} else {
			$user_id = $this->_session('uid');
			$this->display();
		}
    }
	
}
?>
