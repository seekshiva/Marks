<?php
session_start();
if(isset($_SESSION['user'])) if($_SESSION['user'] != "0") header("Location: ./");
if(isset($_POST['user']) && isset($_POST['pass'])) {
include("functions.lib.php");
$res = mysql_query("SELECT `user_type` FROM `users` WHERE `user_name` = '" . $_POST['user'] . "' AND `password` = '" . MD5($_POST['pass']) . "' LIMIT 1");
if(mysql_num_rows($res) == 0) {

}
else {
$row = mysql_fetch_assoc($res);
$_SESSION['user'] = $_POST['user'];
$_SESSION['user_type'] = $row['user_type'];
header("Location:./index.php");
}
}
?>
<!doctype html>
<html>
<head>
<title>Marks App - Login</title>
<style>
body {margin-top:100px; }
table {margin-bottom:100px; }
input {padding:5px; }
</style>
</head>
<body>
<h1 align="center">Marks App</h1>
<form action="" method="post">
<div id="wrapper">
<table align="center">
<tr>
<td><label for="user">Username</label></td>
<td><input type="text" name="user" id="user"></td>
</tr>
<tr>
<td><label for="pass">Password</label></td>
<td><input type="password" name="pass" id="pass"></td>
</tr>
<tr>
<td colspan="2" align="center"><input type="submit" value="Login"></td>
</tr>
</table>
</div>
</form>
<hr />
<em>&copy; Shiva Nandan 2011-12</em>
</body>
</html>