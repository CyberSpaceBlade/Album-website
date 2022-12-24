<?php
session_start();
$getname="";
$getword="";
if (isset($_POST['user']) && isset($_POST['pass']))
{
    $getname=$_POST['user'];//获取提交用户名
    $getword=$_POST['pass'];//获取提交密码
}
//echo "{$getname}"."{$getword}";


if($getname==null||$getword==null){
    echo "<script>alert('不要乱填啊')</script>";//弹出消息框
    die("账号和密码不能为空!");//结束并返回文本
}//判断用户名和密码是不是空的

$dsn = 'mysql:host=103.79.78.236;port=3306;dbname=mydb;charset=utf8';
$username = 'root';
$password = 'qHx@114514';

try {$dbh = new PDO($dsn, $username, $password);}
catch (PDOException $e)
{
    echo '报错信息：' . $e->getMessage();
}

$sql="SELECT * from `user` where username="."'{$getname}'and password="."'{$getword}'".";";
$ps=$dbh->query($sql);
if($ps->rowCount())
{
    echo "<script>alert('登录成功！')</script>";
    Header("refresh:0;url=../All-user/{$getname}/homepage.html");
}
else
{
    echo "<script>alert('输入有错误！请重新登录')</script>";
    Header("refresh:0;url=login.html");
}
$_SESSION['username']=$getname;
?>
