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
	num = 2;	
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
		<div id="balance">
			<div class="span2">
				<h4>Balance:</h4>
				<h3 style="color:#5bb75b; margin-top:-15px;">00.00<span style="color:#999; font-size:18px;">RMB</span></h3>
			</div>
			<div class="span2" style="margin-top:10px;">
				<button class="btn btn-large btn-warning">Charge</button>
			</div>
		</div>

		<div class="horizon-line span10" style="margin-top:0px;">

		</div>

		<div id="bankInfo">
			<div class="span6">
				<h4>Bank Card:<span><label>Connected to 2 cards</label></span></h4>
			</div>
			<div class="span10" style="margin-left:-5px;">
				
				<div class="card span2">
    				<h2 class="card-name">中国文明银行</h2>
   				
	   				<div class="card-context">
	        			<div class="card-number">
	            			<h5 class="h5style">No.1111 2222 3333</h5>
	        			</div>
	    			</div>
	    			
	    			<div class="card-operate">
	                     <button class="btn btn-small btn-info">Disconnect</button>
	            	</div>
				</div>

				<div class="card span2">
    				<h2 class="card-name">中国文明银行</h2>
   				
	   				<div class="card-context">
	        			<div class="card-number">
	            			<h5 class="h5style">No.1111 2222 3333</h5>
	        			</div>
	    			</div>
	    			
	    			<div class="card-operate">
	                     <button class="btn btn-small btn-info">Disconnect</button>
	            	</div>
				</div>

				<div class="card span2">
	                <div class="card-number" style="margin-top:40px;">
	                	<button class="btn btn-large btn-warning">Add a Card</button>						
					</div>
				</div>

				
				
			</div>
		</div>
	</div>
</div>
</div>

<div class="footer">
	<div id="footer-link">
		<a href="#">About </a>|
		<a href="#">Manage </a>|
		<a href="#">Contact Us </a>
		<p>All Copyright Reserved By Civi@2013</p>
	</div>
	
</div>
</body>
</html>