<?php
include_once "./lib.php";
include_once "./header.php";

if (!($user = isLogin())) {
    header('location: index.php');
}

$u = G('u');
$r = connredis();
if (!$prouid = $r->get('user:username:'.$u.':userid')) {
  error('非法用户');
  exit;
}

// 判断该用户我是否已经关注
$isf = $r->sIsMember('following:'.$user['userid'], $prouid);
$isfstatus = $isf ? '0' : '1';
$isfword = $isf ? '取消关注' : '关注ta';
?>

<h2 class="username"><?php echo $u; ?></h2>
<a href="follow.php?uid=<?php echo $prouid; ?>&f=<?php echo $isfstatus; ?>" class="button"><?php echo $isfword; ?></a>

<div class="post">
  <a class="username" href="profile.php?u=test">test</a>
  world<br>
  <i>11 分钟前 通过 web发布</i>
</div>

<div class="post">
  <a class="username" href="profile.php?u=test">test</a>
  hello<br>
  <i>22 分钟前 通过 web发布</i>
</div>

<?php include_once "./footer.php"; ?>
