<?php
include_once "./lib.php";
include_once "./header.php";

if (!isLogin()) {
    header('location: index.php');
}

// 获取 redis 实例
$r = connredis();

// 最新注册的 50 名用户名字
$newuserlist = $r->sort('newuserlink', ['sort' => 'desc', 'get' => 'user:userid:*:username']);
?>

<h2>热点</h2>
<i>最新注册用户(redis中的sort用法)</i><br>
<div>
  <?php foreach ($newuserlist as $username) { ?>
  <a class="username" href="profile.php?u=<?php echo $username; ?>"><?php echo $username; ?></a>
  <?php } ?>
</div>

<br><i>最新的50条微博!</i><br>
<div class="post">
  <a class="username" href="profile.php?u=test">test</a>
  world<br>
  <i>22 分钟前 通过 web发布</i>
</div>

<div class="post">
  <a class="username" href="profile.php?u=test">test</a>
  hello<br>
  <i>22 分钟前 通过 web发布</i>
</div>

<?php include_once "./footer.php"; ?>
