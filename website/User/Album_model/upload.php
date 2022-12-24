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
function change_homepage()
{
    $dir = "img";
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
    fclose($fp);

    $dsn = 'mysql:host=103.79.78.236;port=3306;dbname=Album;charset=utf8';
    $username = 'root';
    $password = 'qHx@114514';
    try {$dbh = new PDO($dsn, $username, $password);} catch (PDOException $e) {echo '报错信息：' . $e->getMessage();}

    $photoname=$_FILES['upload_file']['name'];
    $path = str_replace('\\','/',__FILE__);$temp=explode("/",$path);
    $albumname=$temp[sizeof($temp)-2];
    $date=date('Y-m-d');
    $size=filesize("img/".$photoname)/1000;

    $sql_check="select * from `Album`.`photo` where Name="."'{$photoname}' and albumname="."'{$albumname}'";
    $ps=$dbh->query($sql_check);
    if($ps->rowCount())
    {
        echo "<script>alert('当前图片已存在,请重新上传')</script>";
        Header("refresh:0;url=upload.php");
    }
    else
    {
        $sql="insert into `Album`.`photo`(`Name`,`albumname`,`Date`,`Size`,`username`)values ('{$photoname}','{$albumname}','{$date}','{$size}','{$_SESSION['username']}');";
        $ps1=$dbh->prepare($sql);
        $ps1->execute();

        $ps2=$dbh->query($sql_check);
        if($ps2->rowCount())
        {
            echo "<script>alert('相片上传成功！')</script>";
        }
    }
    $albumname="../".$temp[sizeof($temp)-2];
    $album_size=size_dir($albumname)/1000;  //为求大小需要给出路径，但数据表中只要名字
    $albumname=$temp[sizeof($temp)-2];
    $sql_album_update="update `Album`.`album` set `Size`="."'{$album_size}' where `Name`="."'{$albumname}';";
    $ps=$dbh->prepare($sql_album_update);
    $ps->execute();
    if($ps->rowCount())
    {
        echo "<script>alert('相册大小已修改！')</script>";
    }
    Header("refresh:0;url=show.html");
}



define("UPLOAD_PATH", "img");

$is_upload = false;
$msg = null;
if (isset($_POST['submit']))
{
    if (($_FILES['upload_file']['type'] == 'image/jpg') || ($_FILES['upload_file']['type'] == 'image/jpeg') || ($_FILES['upload_file']['type'] == 'image/png') || ($_FILES['upload_file']['type'] == 'image/gif')) {
        $temp_file = $_FILES['upload_file']['tmp_name'];
        $img_path = UPLOAD_PATH . '/' . $_FILES['upload_file']['name'];
        if (move_uploaded_file($temp_file, $img_path)) {
            $is_upload = true;
        } 
    }
    else
    {
        $msg = '文件类型不正确，请重新上传！';
    }

    if ($is_upload == true)
    {
        change_homepage(); //此处的上传只是图片的上传，并未写入数据库
    }
    else
    {
        echo "<script>alert('上传失败！请重新上传')</script>";
        Header("refresh:0;url=upload.php");
    }
}

?>

<div id="upload_panel">
    <ol>
        <li>
            <p>上传一个图片到相册中</p>
        </li>
        <li>
            <h3>上传区</h3>
            <form enctype="multipart/form-data" method="post" onsubmit="return checkFile()">
                <p>请选择要上传的图片：<p>
                <input class="input_file" type="file" name="upload_file"/>
                <input class="button" type="submit" name="submit" value="上传"/>
            </form>
            <div id="msg">
            </div>
            <div id="img">
                <?php
                    if($is_upload){
                        echo '<img src="'.$img_path.'" width="250px" />';
                    }
                ?>
            </div>
        </li>
	</ol>
</div>

<script type="text/javascript">  //前端检查
    function checkFile() {
        var file = document.getElementsByName('upload_file')[0].value;
        if (file == null || file == "") {
            alert("请选择要上传的文件!");
            return false;
        }
        //定义允许上传的文件类型
        var allow_ext = ".jpg|.jpeg|.png|.gif";
        //提取上传文件的类型
        var ext_name = file.substring(file.lastIndexOf("."));
        //判断上传文件类型是否允许上传
        if (allow_ext.indexOf(ext_name) == -1) {
            var errMsg = "该文件不允许上传，请上传" + allow_ext + "类型的文件,当前文件类型为：" + ext_name;
            alert(errMsg);
            return false;
        }
    }
</script>