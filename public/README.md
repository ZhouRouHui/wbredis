设计 user 表 -- 对应的 key 规则

注册用户
incr global:userid
set user:userid:1:username loedan
set user:userid:1:password 123456

set user:username:zhangsan:userid 1


发微博
incr global:postid
set post:postid:1:time 当前时间时间戳
set post:postid:1:userid 5
set post:postid:1:content 微博内容微博内容微博内容
