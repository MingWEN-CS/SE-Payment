<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
   	<title>Online Payment System</title>
   	<link rel="stylesheet" href="../Public/css/bootstrap.css"/>
   	<link rel="stylesheet" href="../Public/css/index.css"/>
	<script src="../Public/js/jquery-1.9.1.js" type="text/javascript"></script>
</head>

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

<div id="container" style="margin-top:100px;height:400px;">
	<div id="search-wrapper"> 
	<form class="form-search" method="post" action="__APP__/Purchase/search">
	    <div class="input-append">
			<h4>Search the items you want</h4>
		    <input type="text" class="input-xxlarge search-query" placeholder="input the keywords to search">
		    <button type="submit" class="btn btn-primary">Search</button>
		</div>
	</form>
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