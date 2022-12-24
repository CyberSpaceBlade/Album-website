<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>站主页面</title>
</head>
<body>
<h1 align="center">管理员总页面</h1>
<form method="POST" action="">
    <p align="center"><a href="show_liuyan.php"><input type="button" value="查看全部留言"></a></p>
</form>
<table align="center" border="1px" cellspacing="0px" width="800px">
    <tr>
        <th>用户名</th>
        <th>密码</th>
        <th>管理员操作</th>
    </tr>

    <?php

    $dsn = 'mysql:host=103.79.78.236;port=3306;dbname=mydb;charset=utf8';
    $username = 'root';
    $password = 'qHx@114514';

    try {$dbh = new PDO($dsn, $username, $password);}
    catch (PDOException $e) {echo '报错信息：' . $e->getMessage();}
    $sql="select username,password from user where username !='admin'";
    $ps=$dbh->query($sql);
    $result=array();$result=$ps->fetchAll();
    for($i=0;$i<sizeof($result);$i++)
    {
        $temp_username=$result[$i]['username'];
        $temp_password=$result[$i]['password'];
        echo '<tr align="center">';
        echo "<td>$temp_username</td><td>$temp_password</td>
              <td>
              <a href='delete.php?name=$temp_username'><input type='submit' value='删除该用户' /></a>
              <a href='../User/All-user/$temp_username/homepage.html'><input type='submit' value='访问用户相册管理主页' /></a>
              </td>";
        echo '</tr>';
    }

    ?>
</table>

</body>
</html>
