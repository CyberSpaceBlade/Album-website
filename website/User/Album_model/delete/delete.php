<?php
session_start();
function size_dir($path1)
{
    $dirHandle = opendir($path1);$size=0;
    while (($fileName = readdir($dirHandle)) !=false )
    {
        $subFile = $path1 . DIRECTORY_SEPARATOR . $fileName;
        if (is_file($subFile))
        {
            $size+=filesize($subFile);
        }
        elseif (is_dir($subFile) && str_replace('.', '', $fileName) != '')
        {
            $size+=size_dir($subFile);
        }
    }
    closedir($dirHandle);
    return $size; //单位是B
}

$path = str_replace('\\','/',__FILE__);$temp=explode("/",$path);
//相册公开表明任意人员可以上传和查看，但删除还是得分人
$creator=$temp[sizeof($temp)-5];
if($_SESSION['username']==$creator || $_SESSION['username']=="admin")  //有权限修改
{
    $getname="";$dir = "img";
    if (isset($_POST['name']))
    {
        $getname=$_POST['name'];//获取提交相片名
    }
    $photopath=$dir.DIRECTORY_SEPARATOR.$getname;
    unlink($photopath);//第一步，删除图片;第二步，删除数据库记录并重置首页;第三步，更新相册大小;

    copy("show_model.html","show.html");
    $result = array();
    $files = scandir($dir);

    foreach($files as $file)
    {
        switch(ltrim(strstr($file, '.'), '.'))
        {
            case "jpg": case "jpeg":case "png":case "gif":
            $result[] = $dir . "\\" . $file;
        }
    }
    $fp=fopen("show.html",'r');
    $i=0;while($i!=19) {fgets($fp);$i++;}
    $temp1=array();while(!feof($fp)) {$temp1[]=fgets($fp);}  //把要插入的先记录下来
    fclose($fp);

    $fp=fopen("show.html",'r+');
    $i=0;while($i!=19) {fgets($fp);$i++;}

    for($i=0;$i<sizeof($result);$i++)
    {
        $str1='      <a href="'.$result[$i].'" class="image">';
        $str2='        <img src="'.$result[$i].'" alt="">';
        $str3='      </a>';
        fwrite($fp,$str1."\n");
        fwrite($fp,$str2."\n");
        fwrite($fp,$str3."\n");
    }
    for($i=0;$i<sizeof($temp1);$i++) {fwrite($fp,$temp1[$i]);}
    fclose($fp);//至此相片删除，首页清除，下面开始数据库处理

    $dsn = 'mysql:host=103.79.78.236;port=3306;dbname=Album;charset=utf8';
    $username = 'root';
    $password = 'qHx@114514';
    try {$dbh = new PDO($dsn, $username, $password);} catch (PDOException $e) {echo '报错信息：' . $e->getMessage();}
    $sql_check="select * from `Album`.`photo` where Name="."'{$getname}' and username="."'{$_SESSION['username']}'".";";
    $ps=$dbh->query($sql_check);
    if(!$ps->rowCount())
    {
        echo "<script>alert('相片删除失败！当前相片不存在！')</script>";Header("refresh:0;url=delete.php");
    }
    else
    {
        $sql="delete from `Album`.`photo` where Name="."'{$getname}';";
        $ps1=$dbh->prepare($sql);
        $ps1->execute();

        $ps2=$dbh->query($sql_check);
        if(!$ps2->rowCount())
        {
            echo "<script>alert('相片删除成功！')</script>";
        }
    }//数据库清理完成，开始更新相册大小

    $albumname="../".$temp[sizeof($temp)-3];
    $album_size=size_dir($albumname)/1000;  //为求大小需要给出路径，但数据表中只要名字
    $albumname=$temp[sizeof($temp)-3];
    $sql_album_update="update `Album`.`album` set `Size`="."'{$album_size}' where `Name`="."'{$albumname}';";
    $ps=$dbh->prepare($sql_album_update);
    $ps->execute();
    if($ps->rowCount())
    {
        echo "<script>alert('相册大小已修改！')</script>";
    }
}
else
{
    echo "<script>alert('你无权删除相片！')</script>";
}

Header("refresh:0;url=../show.html");
?>



