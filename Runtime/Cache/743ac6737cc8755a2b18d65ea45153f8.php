<?php if (!defined('THINK_PATH')) exit();?><div class="navbar navbar-inverse navbar-fixed-top">
<div class="navbar-inner">
  <div class="container">
  <a class="brand" href="#">Online Payment System</a>
    <div class="nav-collapse collapse">
      <ul class="nav">
        <li class="">
          <a href="./index.php/Index/register">注册</a>
        </li>
        <li class="">
          <a href="./index/php/Index/me">查看</a>
        </li>
        <li><?php echo ($data[0]); ?></li>
      </ul>    
    </div>
  </div>
</div>
</div>