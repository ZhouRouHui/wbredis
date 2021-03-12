<?php

/**
 * 基础函数库
 */

/**
 * 获取 $_POST 的参数
 * @param $key
 * @return mixed
 */
function P($key) {
    return $_POST[$key];
}

/**
 * 获取 $_GET 的参数
 * @param $key
 * @return mixed
 */
function G($key) {
    return $_GET[$key];
}

/**
 * 输出错误信息
 * @param $msg
 */
function error($msg) {
    echo $msg;
    include_once './footer.php';
    exit;
}

/**
 * 连接 redis
 * @return Redis
 */
function connredis() {
    static $r = null;
    if ($r) {
        return $r;
    }
    $r = new Redis();
    $r->connect('redis');
    return $r;
}

/**
 * 判断用户是否登录
 * @return array|false
 */
function isLogin() {
    $userid = $_COOKIE['userid'];
    $username = $_COOKIE['username'];
    $authsecret = $_COOKIE['authsecret'];
    $r = connredis();
    if (!$userid || !$username || !$authsecret || $authsecret != $r->get('user:userid:' . $userid . ':authsecret')) {
        return false;
    }

    return [
        'userid' => $userid,
        'username' => $username
    ];
}

/**
 * 产生随机数
 * @return false|string
 */
function randsecret() {
    $str = 'abcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
    return substr(str_shuffle($str), 0, 16);
}

/**
 * 时间格式转化
 * @param $time
 * @return string
 */
function formattime($time) {
    $dftime = time() - $time;
    switch ($dftime) {
        case 0: return $timestr = '刚刚';
        case ($dftime < 60): return $timestr = $dftime . ' 秒前';
        case ($dftime < 3600): return $timestr = floor($dftime / 60) . '分前';
        case ($dftime < 86400): return $timestr = floor($dftime / 3600) . '小时前';
        default: return $timestr = floor($dftime / 86400) . '天前';
    }
}
