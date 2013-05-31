<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
   	<title>Online Payment System</title>
   	<link rel="stylesheet" href="../Public/css/bootstrap.css"/>
   	<link rel="stylesheet" href="../Public/css/index.css"/>
	<script src="../Public/js/jquery-1.9.1.js" type="text/javascript"></script>
</head>

<body>
<!--
<div class = "header">
	<h1>Online Payment System/<span style="font-size:24px;">Login</span></h1>
</div>
-->
<div class="navbar navbar-inverse navbar-fixed-top">
<div class="navbar-inner">
  <div class="container">
  <a class="brand" href="#">Online Payment System</a>
    <div class="nav-collapse collapse">
      <ul class="nav">
        <li class="">
          <a href="./index.php/Index/register">Register</a>
        </li>
        <li class="">
          <a href="./index/php/Index/login">Home</a>
        </li>
      </ul>    
    </div>
  </div>
</div>
</div>

<div id = "container" style="margin-top:100px;height:400px;">
	<image src="../Public/img/meng.jpg" class = "span7"/>
	<div class="left-center">
	<form class="span3" method="post">
		<h4>Login the system</h4>
		<label for="inputEmail">Username:</label>
		<input type="text" name="username" placeholder="Username">
		<label for="inputPassword">Password:</label>
		<input type="password" name="password" placeholder="Password">
		<button type="submit" class="btn btn-success">Sign In</button>
	</form>
	</div>
</div>

<div class="footer">
	<div id="footer-link">
		<a href="#">About </a>|
		<a href="#">Manage </a>|
		<a href="#">Contact Us </a>
	</div>

	All Copyright Reserved By Civi@2013
</div>
</body>
</html>