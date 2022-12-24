<?php
session_start();
$getname="";
if (isset($_POST['name']))
{
    $getname=$_POST['name'];//获取提交相册名
}
function delete_dir($path1)
{
    $dir=$path1;
    $dir = rtrim($dir, '//');

    $dirHandle = opendir($dir);
    while (($fileName = readdir($dirHandle)) !=false )
    {
        $subFile = $dir . DIRECTORY_SEPARATOR . $fileName;
        if (is_file($subFile))
        {
            unlink($subFile);
        }
        elseif (is_dir($subFile) && str_replace('.', '', $fileName) != '')
        {
            delete_dir($subFile);
        }
    }
    closedir($dirHandle);
    rmdir($path1);
}
$dst_path=dirname(__FILE__);
delete_dir("../Albums/{$getname}");

$dsn = 'mysql:host=103.79.78.236;port=3306;dbname=Album;charset=utf8';
$username = 'root';
$password = 'qHx@114514';
try {$dbh = new PDO($dsn, $username, $password);} catch (PDOException $e) {echo '报错信息：' . $e->getMessage();}

$sql_check="select * from `Album`.`album` where Name="."'{$getname}' and username="."'{$_SESSION['username']}'".";";
$ps=$dbh->query($sql_check);
if(!$ps->rowCount())
{
    echo "<script>alert('相册删除失败！当前相册不存在！')</script>";Header("refresh:0;url=delete_album.html");
}
else
{
    $sql="delete from `Album`.`album` where Name="."'{$getname}';";
    $ps1=$dbh->prepare($sql);
    $ps1->execute();

    $ps2=$dbh->query($sql_check);
    if(!$ps2->rowCount())
    {
        echo "<script>alert('相册删除成功！')</script>";Header("refresh:0;url=../homepage.html");
    }
}

?>
