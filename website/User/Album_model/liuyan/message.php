<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>留言板</title>
</head>
<body>

<?php
session_start();
if(isset($_POST['message'])) {$message = $_POST['message'];}

$dsn = 'mysql:host=103.79.78.236;port=3306;dbname=Album;charset=utf8';
$username = 'root';
$password = 'qHx@114514';

try {$dbh = new PDO($dsn, $username, $password);}
catch (PDOException $e) {echo '报错信息：' . $e->getMessage();}

$path = str_replace('\\','/',__FILE__);$temp=explode("/",$path);
$album_owner=$temp[sizeof($temp)-5];
$album_name=$temp[sizeof($temp)-3];

$sql = "insert into liuyan(username,message,album_owner,album_name) values("."'{$_SESSION['username']}','{$message}','{$album_owner}','{$album_name}');";
$result =$dbh->prepare($sql);
$result->execute();
Header("location:liuyan.php");
?>
</body>
</html>
