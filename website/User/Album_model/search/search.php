<?php
session_start();
$getname="";
if (isset($_POST['name']))
{
    $getname=$_POST['name'];//获取提交相片名
}
$files = scandir("../img");
foreach($files as $file)
{
    if($file!="." && $file!="..")
    {
        if ($getname == $file)
        {
            Header("location:../img/".$getname);
        }
    }
}
?>

