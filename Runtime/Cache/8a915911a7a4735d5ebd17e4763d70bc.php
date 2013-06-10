<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
   	<title>Online Payment System</title>
   	<link rel="stylesheet" href="../Public/css/bootstrap.css"/>
   	<link rel="stylesheet" href="../Public/css/index.css"/>
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
				<li class="nav1"><a href="__APP__/User/home" onclick="changeNav(1);">Home</a></li>
				<li class="nav2"><a href="__APP__/User/account" onclick="changeNav(2);">Account Management</a></li>
				<li class="nav3"><a href="__APP__/User/record" onclick="changeNav(3);">Payment Record</a></li>
			</ul>
		</div>
	</div>
</div>
<h3>Account</h3>
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