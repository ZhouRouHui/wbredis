<?php
include_once "./lib.php";
include_once "./header.php";

if (!($user = isLogin())) {
    header('location: index.php');
}


// 接收参数
$uid = G('uid');
$f = G('f');
// 判断参数合法性
if ((int)$uid <= 0 || !in_array($f, [0, 1])) {
    error('参数错误');
    exit;
}
// 不可关注自己
if ($uid == $user['userid']) {
    error('非法操作');
    exit();
}

$r = connredis();

if ($f) {
    // 关注
    $r->sAdd('following:' . $user['userid'], $uid);
    $r->sAdd('follower:' . $uid, $user['userid']);
} else {
    // 取消关注
    $r->sRem('following:' . $user['userid'], $uid);
    $r->sRem('follower:' . $uid, $user['userid']);
}


$uname = $r->get('user:userid:'.$uid.':username');

header('location: profile.php?u='.$uname);


include_once "./footer.php";

?>