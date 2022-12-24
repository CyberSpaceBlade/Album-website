<?php
session_start();
copy("show_album_model.html","show_album.html");
$tempfiles=scandir("../Albums");
$result = array();

$path = str_replace('\\','/',__FILE__);$temp=explode("/",$path);
$creator=$temp[sizeof($temp)-3];

if(sizeof($tempfiles)>=3)  //有至少一个相册才进行后续操作，否则直接跳到最后结束返回一个空页面
{
    $dsn = 'mysql:host=103.79.78.236;port=3306;dbname=Album;charset=utf8';
    $username = 'root';
    $password = 'qHx@114514';
    try {$dbh = new PDO($dsn, $username, $password);} catch (PDOException $e) {echo '报错信息：' . $e->getMessage();}

    if($_SESSION['username']==$creator || $_SESSION['username']=='admin') //返回所有相册
    {
        foreach($tempfiles as $file)
        {if($file!="." && $file!="..") {$result[]=$file;}}
    }
    else  //外来用户访问，需要对所有相册权限进行审查
    {
        foreach($tempfiles as $file)
        {
            if($file!="." && $file!="..")
            {
                $sql1="select Power from `Album`.`album` where Name="."'{$file}' and username="."'{$creator}';";
                $ps=$dbh->query($sql1);
                $list=array();$list=$ps->fetch();
                $temp_power=$list[0];  //相册权限
                if($temp_power=='public')  //只有设定为公开的相册才会被加入结果
                {
                    $result[]=$file;
                }
            }
        }
    }
}

$fp=fopen("show_album.html","r");
$i=0;
while($i!=10)
{
    fgets($fp);
    $i++;
}
$temp1=array();while(!feof($fp)) {$temp1[]=fgets($fp);}  //把要插入的先记录下来
fclose($fp);

$i=0;
$fp=fopen("show_album.html","r+");
while($i!=10)
{
    $ceshi=fgets($fp);
    $i++;
}

for($i=0;$i<sizeof($result);$i++)
{fwrite($fp,'<a href="../Albums/'.$result[$i].'/show.html"><button>点我进入相册'.$result[$i].'</button></a>'."\n");}
for($i=0;$i<sizeof($temp1);$i++) {fwrite($fp,$temp1[$i]);}
fclose($fp);
Header("location:show_album.html");
?>