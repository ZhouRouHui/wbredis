<?php
include_once "./header.php";
include_once "./lib.php";

if (!($user = isLogin())) {
    header('location: index.php');
    exit;
}

$r = connredis();
/**
 * 推模型下，取出自己发的和粉主推过来的信息
 */
$r->lTrim('recivepost:' . $user['userid'], 0, 49);
//$newpost = $r->sort('recivepost:'.$user['userid'], ['sort' => 'desc', 'get' => 'post:postid:*:content']);
$newpost = $r->sort('recivepost:' . $user['userid'], ['sort' => 'desc']);

// 计算几个粉丝，几个关注
$myfans = $r->sCard('follower:' . $user['userid']);
$mystar = $r->sCard('following:' . $user['userid']);

?>

<div id="postform">
  <form method="POST" action="post.php">
      <?php echo $user['username']; ?>, 有啥感想?
    <br>
    <table>
      <tr>
        <td><textarea cols="70" rows="3" name="status"></textarea></td>
      </tr>
      <tr>
        <td align="right"><input type="submit" name="doit" value="Update"></td>
      </tr>
    </table>
  </form>
  <div id="homeinfobox">
      <?php echo $myfans; ?> 粉丝<br>
      <?php echo $mystar; ?> 关注<br>
  </div>
</div>

<?php
foreach ($newpost as $postid) {
    $post = $r->hGetAll('post:postid:' . $postid);
    ?>
  <div class="post">
    <a class="username"
       href="profile.php?u=<?php echo $post['username']; ?>"><?php echo $post['username']; ?></a> <?php echo $post['content']; ?><br>
    <i><?php echo formattime($post['time']); ?>通过 web 发布</i>
  </div>
<?php } ?>

<?php include_once "./footer.php"; ?>
