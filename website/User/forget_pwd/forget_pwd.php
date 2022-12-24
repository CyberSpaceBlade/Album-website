<?php

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

$dsn = 'mysql:host=127.0.0.1;port=3306;dbname=mydb;charset=utf8';
$username = 'root';
$password = 'qHx@114514';

try {$dbh = new PDO($dsn, $username, $password);}
catch (PDOException $e)
{
    echo '报错信息：' . $e->getMessage();
}

$sql="SELECT * from `user` where username="."'{$getname}'".";";
$ps=$dbh->query($sql);
if($ps->rowCount())  //查找到该用户
{
    $sql1="update  `user` set `password`='{$getword}' where `username`='{$getname}'";
    $ps1=$dbh->prepare($sql1);
    $ps1->execute();
    $sql2="SELECT * from `user` where username="."'{$getname}'and password="."'{$getword}'".";";
    $ps2=$dbh->query($sql);
    if($ps2->rowCount())
    {
        echo "<script>alert('密码修改成功！')</script>";
        Header("refresh:0;url=../login/login.html");
    }
}
else
{
    echo "<script>alert('用户不存在！请注册or输入正确的用户名！')</script>";
    Header("refresh:0;url=../forget_pwd/forget_pwd.html");
}
?>
