<?php
include_once './header.php';
include_once './lib.php';

/**
 * 注册用户
 * set user:userid:1:username loedan
 * set user:userid:1:password 123456
 * set user:username:zhangsan:userid 1
 *
 * userid 生成
 * incr global:userid
 *
 * 具体步骤
 * 0. 接收 $_POST 参数，判断用户名密码是否完整
 * 1. 连接 redis，查询该用户名，判断是否存在
 * 2. 用户名和密码写入 redis
 * 3. 完成登录操作
 */

if (!!isLogin()) {
    header('location: home.php');
    exit;
}

$username = P('username');
$password = P('password');
$password2 = P('password2');

if (!$username || !$password || !$password2) {
    error('请输入完整信息');
}

if ($password != $password2) {
    error('两次密码不一致');
}

// 连接 redis
$r = connredis();

// 查询用户名是否已被注册
if ($r->get('user:username:'.$username.':userid')) {
    error('用户名已被注册，请更换');
}

// 获取 userid
$userid = $r->incr('global:userid');

// 用户信息注册进 redis
$r->set('user:userid:'.$userid.':username', $username);
$r->set('user:userid:'.$userid.':password', $password);
$r->set('user:username:'.$username.':userid', $userid);

// 通过一个链表，维护 50 个最新的 userid
$r->lPush('newuserlink', $userid);  // 存在链表左侧
$r->lTrim('newuserlink', 0, 49);    // 对一个列表进行修剪(trim)，就是说，让列表只保留指定区间内的元素，不在指定区间之内的元素都将被删除。

echo '注册成功';

include_once './footer.php';

?>