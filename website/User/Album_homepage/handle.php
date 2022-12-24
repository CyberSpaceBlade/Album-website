<?php

$getname="";
if (isset($_POST['Username']))
{
    $getname=$_POST['Username'];//获取提交相册名
}
$newpath="../"."{$getname}"."/show_album/show_album.php";
Header("location:{$newpath}");

?>