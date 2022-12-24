<?php
function copy_dir($path1,$path2)
{
    $dir=$path1;
    $dir = rtrim($dir, '//');

    $path=$path2;
    mkdir($path,0777,true);
    $dirHandle = opendir($dir);
    while (($fileName = readdir($dirHandle)) !=false )
    {
        $subFile = $dir . DIRECTORY_SEPARATOR . $fileName;
        if (is_file($subFile))
        {
            $b=$path."/".$fileName;
            //echo $subFile." ".$b."\n";
            copy($subFile,$b);
        }
        elseif (is_dir($subFile) && str_replace('.', '', $fileName) != '')
        {
            $c=$path."/".basename($subFile);
            mkdir($c,0777,true);      //创建文件夹
            copy_dir($subFile,$c);
        }
    }
    closedir($dirHandle);
}


$getname="";
$getword="";
if (isset($_POST['user']) && isset($_POST['pass']))
{
    $getname=$_POST['user'];//获取提交用户名
    $getword=$_POST['pass'];//获取提交密码
}

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

$sql="SELECT * from `user` where username="."'{$getname}'".";";
$ps=$dbh->query($sql);
if($ps->rowCount())
{
    echo "<script>alert('用户已存在！请重新注册')</script>";
    Header("refresh:0;url=register.html");
}
else
{
    $sql1="INSERT into  `user`(`username`,`password`) values ('{$getname}','{$getword}');";
    $ps1=$dbh->prepare($sql1);
    $ps1->execute();

    $ps2=$dbh->query($sql);
    if($ps2->rowCount())
    {
        echo "<script>alert('新建成功！请登录')</script>";
    }
    mkdir("../All-user/{$getname}",0777,true);  //创立同名文件夹，后续的相册文件夹就在该文件夹下
    copy_dir("../Album_homepage","../All-user/{$getname}");
    Header("refresh:0;url=../login/login.html");
}
?>
