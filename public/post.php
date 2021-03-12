<?php
include_once './lib.php';
include_once './header.php';

/**
 * incr global:postid
 * set post:postid:1:time 当前时间时间戳
 * set post:postid:1:userid 5
 * set post:postid:1:content 微博内容微博内容微博内容
 *
 * 0. 判断是否登录
 * 1. 接收 post 参数
 * 2. set redis
 */

if (!$content = P('status')) {
    error('请输入内容');
}

if (!($user = isLogin())) {
    header('location: index.php');
    exit;
}

$r = connredis();

$postid = $r->incr('global:postid');
//$r->set('post:postid:'.$postid.':userid', $user['userid']);
//$r->set('post:postid:'.$postid.':time', time());
//$r->set('post:postid:'.$postid.':content', $content);
$r->hmset('post:postid:'.$postid, ['userid' => $user['userid'], 'username' => $user['username'], 'time' => time(), 'content' => $content]);

/**
 * 推模型，用户每发布一篇微博，就把微博推给自己的粉丝
 */
$fans = $r->sMembers('follower:' . $user['userid']);
$fans[] = $user['userid'];
foreach ($fans as $fansid) {
    $r->lPush('recivepost:' . $fansid, $postid);
}

header('location: home.php');
exit;
