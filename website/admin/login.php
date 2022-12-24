<?php
session_start();
$getname="";
$getword="";
if (isset($_POST['adminusername']) && isset($_POST['adminpassword']))
{
    $getname=$_POST['adminusername'];//获取提交用户名
    $getword=$_POST['adminpassword'];//获取提交密码
}

$dsn = 'mysql:host=103.79.78.236;port=3306;dbname=mydb;charset=utf8';
$username = 'root';
$password = 'qHx@114514';

try {$dbh = new PDO($dsn, $username, $password);}
catch (PDOException $e) {echo '报错信息：' . $e->getMessage();}

$sql="SELECT isAdmin from `user` where username="."'{$getname}'and password="."'{$getword}'".";";
$ps=$dbh->query($sql);
$temp=array();$temp=$ps->fetch();

if($temp[0])   //是管理员
{
    echo "<script>alert('欢迎回来，管理员！')</script>";
    Header("refresh:0;url=admin.php");
}
else
{
    echo "<script>alert('账号或密码有错误！')</script>";
    Header("refresh:0;url=admin.html");
}
$_SESSION['username']=$getname;
?>
