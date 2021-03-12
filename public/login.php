<?php
include_once './lib.php';
include_once './header.php';

/**
 * 登录页面
 * 0. 接收 $_POST，判断完整性
 * 1. 通过 username 获取 userid
 * 2. 通过 userid 获取 password
 * 3. 比对 password 是否匹配
 * 4. 写入 cookie，登录成功
 */

if (!!isLogin()) {
    header('location: home.php');
    exit;
}

// 接收 $_POST，判断完整性
$username = P('username');
$password = P('password');

if (!$username || !$password) {
    error('请输入用户名或密码');
}

// 通过 username 获取 userid
$r = connredis();
if (!$userid = $r->get('user:username:'.$username.':userid')) {
    error('用户不存在');
}

// 通过 userid 获取 password，比对 password 是否匹配
$redisP = $r->get('user:userid:'.$userid.':password');
if ($redisP != $password) {
    error('用户名或密码错误');
}

// 写入 cookie，登录成功
$authsecret = randsecret();
setcookie('username', $username);
setcookie('userid', $userid);
setcookie('authsecret', $authsecret);

// 将随机字符串绑定到用户登录信息中，防止浏览器更改cookie信息伪造其他用户登录状态
$r->set('user:userid:'.$userid.':authsecret', $authsecret);

header('location: home.php');

?>
