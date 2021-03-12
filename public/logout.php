<?php
include_once './lib.php';

$userid = $_COOKIE['userid'];
setcookie('userid', '', -1);
setcookie('username', '', -1);
setcookie('authsecret', '', -1);

// 退出时删除登录时保存的随机数盐值
$r = connredis();
$r->set('user:userid:'.$userid.':authsecret', '');

header('location: index.php');
