<?php
class AuditorAction extends Action {
	//The home page!
	public function home() {
		if(!session('?name'))
			$this->redirect('Index/index');
		$this->display();
	}
	
	//Log in the auditor center, just for test.
	public function log1() {
		$name = $_GET['name'];
		session('name', $name);
		session('id', 123);
		$this->redirect('home');
	}
	
	//log out, redirect to the index interface
	public function logout() {
		if(!session('?name'))
			$this->redirect('Index/index');
		session(null);
		$this->redirect('Admin/login');
	}
	
	//change auditor's password
	public function changePasswd() {
		$old_pwd = $_POST['old_pwd'];
		$new_pwd = $_POST['new_pwd'];
		$Model = new Model();
		$id = $_SESSION['id'];
		$pwd = $Model->query("	select passwd
								from se_auditor
								where id = $id ");

		if($old_pwd != $pwd[0]['passwd']) {
			$this->ajaxReturn('','Password incorrect',0);
		}
		else {
			$Model->query("	update se_auditor
							set passwd = $new_pwd
							where id = $id");
			$this->ajaxReturn('', 'success' ,1);
		}
	}

	//show unhandled disputes
	public function dispute() {
		if(!session('?name'))
			$this->redirect('Index/index');

		$Model = new Model();
		
		//select unhandled disputes from database
		$list =  $Model->table('se_orders, se_dispute')->where('se_orders.ID = se_dispute.oid AND se_orders.STATE = "auditing"')->order('ID')->field('se_orders.ID as OID, SELLER, BUYER, buyer_reason, seller_reason')->select();
		$count = count($list);

		for($i = 0; $i < $count; $i++) {
			$oid = $list[$i]['OID'];
			$op = $Model->table('se_order_operation')->where('OID='.$oid)->field('OPERATION, TIME')->select();

			$goods = $Model->table('se_order_goods')->where('OID='.$oid.' AND STATE="auditing"')->order('GID')->field('GID, PRICE, AMOUNT, NAME, IMGURL')->select();

			$list[$i]['goods'] = $goods;
			$list[$i]['size'] = count($goods);
			$list[$i]['state'] = $op;
			$list[$i]['created'] = $op[0]['TIME'];
		}
		$this->assign('list', $list); 
		
		//dump($list);
		$this->display();
	}
	
	//auditor agree for refund
	public function refundAgree() {
		if(!session('?name'))
			$this->redirect('Index/index');

		$oid = $_POST['oid'];
		$model = new Model();

		//update balance
		$order = $model->table('se_orders')->where('ID='.$oid)->find();
		$all = $model->table('se_order_goods')->where('OID='.$oid.' AND STATE="auditing"')->field('PRICE, AMOUNT')->select();
		$num = count($all);
		$sum = 0;
		for($i = 0; $i < $num; $i++) {
			$sum += $all[$i]['PRICE']*$all[$i]['AMOUNT'];
		}

		$model->table('se_user')->where('UID='.$order['BUYER'])->setInc('BALANCE', $sum);
		$model->table('se_user')->where('UID=1')->setDec('BALANCE', $sum);
		$account['oid'] = $oid;
		$account['record'] = 0 - $sum;
		$account['time'] = time();
		$model->table('se_sysaccount')->add($account);
		
		//change order state
		$data['OPERATION'] = 'refunded';
		$data['OID'] = $oid;
		$data['OPERATOR'] = 1;
		$model->table('se_order_operation')->add($data);
		$tmp['STATE'] = 'refunded';
		$tmp['ISAUDIT'] = 'YES';
		$model->table('se_orders')->where('ID='.$oid)->save($tmp);

		//change goods state
		$tmp2['STATE'] = 'refunded';
		$model->table('se_order_goods')->where('OID='.$oid.' AND STATE="auditing"')->save($tmp2);

		//record in auditor_result
		$record['oid'] = $oid;
		$record['gid'] = $gid;
		$record['aid'] = $_SESSION['id'];
		$record['time'] = time();
		$record['result'] = 1;
		$model->table('se_dispute_result')->add($record);

		$this->ajaxReturn(null,null,1);
	}
	
	//auditor refuse to refund 
	public function refundDisagree() {
		if(!session('?name'))
			$this->redirect('Index/index');

		$oid = $_POST['oid'];
		$model = new Model();

		//change order state
		$data['OPERATION'] = 'payed';
		$data['OID'] = $oid;
		$data['OPERATOR'] = 1;
		$model->table('se_order_operation')->add($data);

		$tmp['STATE'] = 'payed';
		$tmp['ISAUDIT'] = 'YES';
		$model->table('se_orders')->where('ID='.$oid)->save($tmp);

		//change goods state
		$tmp2['STATE'] = 'payed';
		$model->table('se_order_goods')->where('OID='.$oid.' AND STATE="auditing"')->save($tmp2);

		//record in auditor_result
		$record['oid'] = $oid;
		$record['aid'] = $_SESSION['id'];
		$record['time'] = time();
		$record['result'] = 0;
		$model->table('se_dispute_result')->add($record);

		$this->ajaxReturn(null,null,1);
	}
	
	//show Reconciliation Data List for one day
	public function showList() {
		$data = $_GET['data'];	//get the date in the form of 'date'
		$day = strtotime($data); //transform the date to Unix timestamp
		$nextday = $day + 86400;	//next day is the last moment of the select date

		$Model = new Model();
		$query = $Model->query("	select count(distinct oid) as num
									from se_order_operation
									where time like '$data%'
										and operation = 'created'");
		$count = $query[0]['num'];
		
		//show the Reconciliation Data List in pages
		import('@.Org.Util.Page');
		$rec_page = new page($count, 30);
		$rec_show = $rec_page->show();
		$up = $rec_page->firstRow;
		$down = $rec_page->listRows;
		$rec_sql = "select id, buyer, seller, totalprice, state, time
				from se_orders, (	select oid, time 
									from se_order_operation
									where time like '$data%'
										and operation = 'created') as temp
				where id = oid 
				order by id "
				.' limit '.$up.','.$down.' ';

		$rec_list = $Model->query($rec_sql);

		//show dispute list
		$dis_list = $Model->table('se_dispute_result, se_dispute, se_orders')->where('se_dispute_result.oid = se_dispute.oid AND se_dispute.oid = se_orders.ID AND se_dispute_result.time>='.$day.' AND se_dispute_result.time <'.$nextday)->order('se_dispute.oid')->field('se_dispute.oid, se_dispute_result.time, BUYER, SELLER, result, aid')->select();
		$dis_count = count($dis_list);
		
		//show error list 
		$err_list = $Model->table('se_audit_error')->where('time>='.$day.' AND time <'.$nextday)->order('oid')->select();
		$err_count = count($err_list);

		$this->assign('data', $data);
		
		$this->assign('rec_list', $rec_list);
		$this->assign('rec_page',$rec_show);
		$this->assign('dis_list', $dis_list);
		$this->assign('dis_count', $dis_count);
		$this->assign('err_list', $err_list);
		$this->assign('err_count', $err_count);

		$this->display('datalist');
	}
	
	// export the Reconciliation Data List as a .xls document
	public function exportList() {
		$data = $_GET['data'];
		$day = strtotime($data);
		$nextday = $day + 86400;
		$Model = new Model();
		$list = $Model->query("select id, buyer, seller, totalprice, state, time
								from se_orders, 
								(select oid, time 
								from se_order_operation
								where time like '$data%'
								and operation = 'created') as temp
								where id = oid 
								order by id");
		$size = count($list);
		//all Reconciliation Data List from 00:00 to 23:59 of the selected day

		//export the list to a .xls document
		header("Content-type:application/vnd.ms-excel; charset=UTF-8");
		header("Content-Disposition:filename=$data.xls");
		echo "Order number\t";
		echo "Buyer ID\t";
		echo "Seller ID\t";
		echo "Total amount\t";
		echo "Order status\t";
		echo "Trade time\n";
		for($i = 0; $i < $size; $i++) {
			$id = $list[$i]['id'];
			$buyer = $list[$i]['buyer'];
			$seller = $list[$i]['seller'];
			$totalprice = $list[$i]['totalprice'];
			$state = $list[$i]['state'];
			$tmp = strtotime($list[$i]['time']);
			$create_time = date("H:i:s", $tmp);
			echo "$id\t";
			echo "$buyer\t";
			echo "$seller\t";
			echo "$totalprice\t";
			echo "$state\t";
			echo "$create_time\n";
		}
	}

	//show all uncorrected errors
	public function error() {
		if(!session('?name'))
			$this->redirect('Index/index');

		$error = M('audit_error');
		$list =  $error->where('iscorrected = 0')->order('time')->select();
		
		$this->assign('list',$list); 
		$this->display();
	}
	
	//correct error
	public function errorCorrect() {
		if(!session('?name'))
			$this->redirect('Index/index');
		$id = $_POST['id'];
		$model = new Model();
		$sys = M('sysaccount');
		$need_pay = $model->table('se_audit_error')->where('oid='.$id)->getField('need_pay');
		$actual_pay = $model->table('se_audit_error')->where('oid='.$id)->getField('actual_pay');
		
		//if a buyer pay more than he need, then return the excess part to the buyer
		if($actual_pay > $need_pay) {
			// update buyer balance
			$model->table('se_user, se_orders, se_audit_error')->where('se_user.uid=se_orders.buyer AND se_audit_error.oid=se_orders.id AND 
			se_orders.id='.$id)->setInc('se_user.balance', $actual_pay - $need_pay);

			// update sysaccoutn balance
			$root = M('user');
			$root->where('UID = 1')->setDec('BALANCE',$actual_pay - $need_pay); 

			// update sysaccount
			$sys->where('oid='.$id)->delete();
			$data['oid'] = $id;
			$data['record'] = $need_pay;
			$data['time'] = time();
			$sys->add($data);
		}
		
		//if the buyer pay less than he need, cancel the order and return the money buyer payed
		else {
			// update buyer balance
			$model->table('se_user, se_orders, se_audit_error')->where('se_user.uid=se_orders.buyer AND se_audit_error.oid=se_orders.id AND 
			se_orders.id='.$id)->setInc('se_user.balance', $actual_pay);

			// update sysaccount balance
			$root = M('user');
			$root->where('UID = 1')->setDec('BALANCE',$actual_pay); 
			$sys->where('oid='.$id)->delete();

			//change the order status
			$data['state'] = "canceled";
			$model->table('se_orders')->where('se_orders.id='.$id)->save($data);
			
			$tmp['OID'] = $id;
			$tmp['OPERATION'] = "canceled";
			$tmp['TIME'] = time();
			$tmp['OPERATOR'] = "auditor";
			$order_op = M('order_operation');
			$order_op->add($tmp);		
		}

		$error = D('audit_error');
		$data['iscorrected'] = 1;
		$error->where('oid='.$id)->save($data);

		$this->ajaxReturn(null,null,1);
	}

	public function test() {
		$model = new Model();
	
		$oid = 2;
		//change order state
		$data['OPERATION'] = 'payed';
		$data['OID'] = $oid;
		$data['OPERATOR'] = 1;
		$model->table('se_order_operation')->add($data);
		$tmp['STATE'] = 'payed';
		$tmp['ISAUDIT'] = 'YES';
		$model->table('se_orders')->where('ID='.$oid)->save($tmp);

		//change goods state
		$tmp2['STATE'] = 'payed';
		$model->table('se_order_goods')->where('OID='.$oid.' AND STATE="auditing"')->save($tmp2);

		//record in auditor_result
		$record['oid'] = $oid;
		$record['aid'] = $_SESSION['id'];
		$record['time'] = time();
		$record['result'] = 0;
		$model->table('se_dispute_result')->add($record);
	}

	public function search() {
		$id = $_GET['id'];
		$model = new Model();

		$order =  $model->table('se_orders')->where('ID='.$id)->find();

		$state = $model->table('se_order_operation')->where('OID='.$id)->order('TIME')->field('OPERATION, TIME')->select();

		$goods = $model->table('se_order_goods')->where('OID='.$id)->select();
		
		$dispute = $model->table('se_dispute_result')->where('oid='.$id)->find();

		$this->assign('size', count($goods));
		$this->assign('order', $order);
		$this->assign('state', $state);
		$this->assign('goods', $goods);
		$this->assign('dispute', $dispute);
		$this->assign('id', $id);
		
		//dump($dispute);
		$this->display();
	}
}
?>