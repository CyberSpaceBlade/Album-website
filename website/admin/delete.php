<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>删除用户信息</title>
</head>

<body>
<?php
if(isset($_GET['name']) && !empty($_GET['name']))
{
    $name = $_GET['name'];
} else {
    echo "未收到参数！";
    header('refresh:1;url=admin.php');
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
delete_dir("../User/All-user/{$name}");//从用户文件夹中清除掉这个文件夹，从文件夹意义上清除用户

$dsn = 'mysql:host=103.79.78.236;port=3306;dbname=mydb;charset=utf8';
$username = 'root';
$password = 'qHx@114514';
try {$dbh = new PDO($dsn, $username, $password);}
catch (PDOException $e) {echo '报错信息：' . $e->getMessage();}
$sql="delete from user where username="."'{$name}';"; //删除用户表记录
$ps=$dbh->prepare($sql);
$ps->execute();

$dsn = 'mysql:host=103.79.78.236;port=3306;dbname=Album;charset=utf8';
try {$dbh = new PDO($dsn, $username, $password);}
catch (PDOException $e) {echo '报错信息：' . $e->getMessage();}
$sql="delete from album where username="."'{$name}';"; //删除相册表记录
$ps=$dbh->prepare($sql);
$ps->execute();
$sql="delete from photo where username="."'{$name}';"; //删除相片表记录
$ps=$dbh->prepare($sql);
$ps->execute();


Header("location:admin.php");
?>

