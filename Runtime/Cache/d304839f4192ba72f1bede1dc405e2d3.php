<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
   	<title>Online Payment System</title>
   	<link rel="stylesheet" href="../Public/css/bootstrap.css"/>
   	<link rel="stylesheet" href="../Public/css/group1.css"/>
	<script src="../Public/js/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="../Public/js/bootstrap.js" type="text/javascript"></script>
</head>

<script>
$(function(){
	num = 1;	
	for (i = 1; i <=3; i++){
		if (i==num)
			$('#userNavbar .nav .nav'+i).addClass("active");
		else $('#userNavbar .nav .nav'+i).removeClass("active");
	}
});
</script>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
<div class="navbar-inner">
  <div class="container">
  <a class="brand" href="__APP__/">Online Payment System</a>
    <div class="nav-collapse collapse">
      <ul class="nav">
        <li class="">
          <a href="__APP__/User/register">Register</a>
        </li>
        <li class="">
          <a href="__APP__/User/home">Home</a>
        </li>
        <li class="">
          <a href="__APP__/Purchase/index">Purchase</a>
        </li>
      </ul>
    </div>
  </div>
</div>
</div>

<div id = "container" style="margin-top:10px;">
<script>
function changeNav(num){
	for (i = 1; i <=3; i++){
		if (i==num)
			$('#userNavbar .nav .nav'+i).addClass("active");
		else $('#userNavbar .nav .nav'+i).removeClass("active");
	}
}	
	
</script>

<div style="margin-top:40px;">
	<div class="navbar">
		<div id="userNavbar" class="navbar-inner">
			<ul class="nav">
				<li class="nav1"><a href="__APP__/User/home" onclick="changeNav(1);">Basic Information</a></li>
				<li class="nav2"><a href="__APP__/User/account" onclick="changeNav(2);">Account Management</a></li>
				<li class="nav3"><a href="__APP__/User/record" onclick="changeNav(3);">Payment Record</a></li>
			</ul>
		</div>
	</div>
</div>

<div id = "content">
	<div id ="wrapper">
		<div id="userInfo">
			<div class="span2">
				<img src="../Public/img/avatar.gif"/>
			</div>
			<div class="span2">
				<p><h4>文明</h4></p>
				<p>821817954@qq.com</p>
				<p><button class="btn btn-danger">Authenticate</button></p>
			</div>		
		</div>
		
		<div id="password" class="span6" style="margin-left:80px;margin-top:25px">
			<button class="btn btn-large btn-warning">Password</button>
			<label></label>
			<a href="#loginPassword" data-toggle="modal">modify your password for login </a>
			<label></label>
			<a href="#paymentPassword" data-toggle="modal">modify your password for payment</a>
		</div>

		<div class="horizon-line span10" style="margin-top:20px;margin-bottom:20px;">
		</div>
		<label></label>
		<div id="otherInfo" style="margin-top:100px;">
			<div class="span9">
				<h4>Other Information:</h4>
				<label>Gender:</label>
				<label>Phone Number:<span>18768113960</span></label>
				<label>Address:<span>浙江大学玉泉校区30舍1001</span></label>
				<button class="btn btn-small btn-danger" data-toggle="modal" data-target="#myModal">Modify</button>
			</div>
		</div>
	</div>

	<div id="loginPassword" class="modal hide fade">
  		<div class="modal-header">
    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    	<h3>Change your password for login</h3>
  		</div>
  		<div class="modal-body">
    		<fieldset class="step">
				<p>
					<label>Password for login<span style="color:#ff0000; font-family:song">*</span>:</label>
					<input type="password" name="password" placeholder="Password" AUTOCOMPLETE=OFF>
				</p>
				<p>	
					<label>New password for login<span style="color:#ff0000; font-family:song">*</span>:</label>
					<input type="password" name="confirmPassword" placeholder="Password" AUTOCOMPLETE=OFF>
				</p>
				<p>	
					<label>Confirm again<span style="color:#ff0000; font-family:song">*</span>:</label>
					<input type="password" name="confirmPassword" placeholder="Password" AUTOCOMPLETE=OFF>
				</p>
			</fieldset>
  		</div>
	  	<div class="modal-footer">
	    	<a href="#" class="btn btn-success">Close</a>
	    	<a href="#" class="btn btn-warning">Save changes</a>
	  	</div>
	</div>

	<div id="paymentPassword" class="modal hide fade">
  		<div class="modal-header">
    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    	<h3>Change your password for payment</h3>
  		</div>
  		<div class="modal-body">
    		<fieldset class="step">
				<p>
					<label>Password for payment<span style="color:#ff0000; font-family:song">*</span>:</label>
					<input type="password" name="password" placeholder="Password" AUTOCOMPLETE=OFF>
				</p>
				<p>	
					<label>New password for payment<span style="color:#ff0000; font-family:song">*</span>:</label>
					<input type="password" name="confirmPassword" placeholder="Password" AUTOCOMPLETE=OFF>
				</p>
				<p>	
					<label>Confirm again<span style="color:#ff0000; font-family:song">*</span>:</label>
					<input type="password" name="confirmPassword" placeholder="Password" AUTOCOMPLETE=OFF>
				</p>
			</fieldset>
  		</div>
	  	<div class="modal-footer">
	    	<a href="#" class="btn btn-success">Close</a>
	    	<a href="#" class="btn btn-warning">Save changes</a>
	  	</div>
	</div>

	<div id="myModal" class="modal hide fade">
  		<div class="modal-header">
    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    	<h3>Modal header</h3>
  		</div>
  		<div class="modal-body">
    		<p>One fine body…</p>
  		</div>
	  	<div class="modal-footer">
	    	<a href="#" class="btn">Close</a>
	    	<a href="#" class="btn btn-primary">Save changes</a>
	  	</div>
	</div>
</div>
</div>


<div class="footer">
	<div id="footer-link">
		<a href="#">About </a>|
		<a href="#">Manage </a>|
		<a href="#">Contact Us </a>
		<p>All Copyright Reserved By Red One@2013</p>
	</div>
	
</div>
</body>
</html>