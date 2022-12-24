<?php
session_start();
$getname="";
$getpower="";
if (isset($_POST['name']) && isset($_POST['power']))
{
    $getname=$_POST['name'];//获取提交相册名
    $getpower=$_POST['power'];//获取权限
}
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

$dst_path=dirname(__FILE__);
copy_dir("../../../Album_model","../Albums/{$getname}");

$dsn = 'mysql:host=103.79.78.236;port=3306;dbname=Album;charset=utf8';
$username = 'root';
$password = 'qHx@114514';
try {$dbh = new PDO($dsn, $username, $password);} catch (PDOException $e) {echo '报错信息：' . $e->getMessage();}

$date=date('Y-m-d');
$sql_check="select * from `Album`.`album` where Name="."'{$getname}' and username="."'{$_SESSION['username']}'".";";
$ps=$dbh->query($sql_check);
if($ps->rowCount())
{
    echo "<script>alert('当前相册已存在！')</script>";Header("refresh:0;url=create_album.html");
}
else
{
    $sql="insert into `Album`.`album`(`Name`,`Date`,`Size`,`username`,`Power`) values ('{$getname}','{$date}',0,'{$_SESSION['username']}','{$getpower}');";
    $ps1=$dbh->prepare($sql);
    $ps1->execute();

    $ps2=$dbh->query($sql_check);
    if($ps2->rowCount())
    {
        echo "<script>alert('相册新建成功！')</script>";Header("refresh:0;url=../homepage.html");
    }
}

?>
