<?php
include("connect.php");
include("functions.lib.php");

if(isset($_GET['teacher'])) {
    mysql_query("INSERT INTO `teachers` (`teacher_name`) VALUES ('" . $_GET['teacher'] . "')");
    header("Location: ./teachers.php");
}
?>
<!doctype html>
<html>
<head>
<title>Student Information</title>
<link rel="stylesheet" href="main.css">
</head>
<body>
<div id="menu">
    <a href="./">Home</a><a href="./student.php">Students</a><a id="currentMenuItem" href="./teachers.php">Teachers</a>
</div>
<div id="wrapper">
<?php

echo "<div class=\"block\">";
echo "<form action=\"\" method=\"GET\"><label from=\"teacher\">Add New Teacher</label> <input type=\"text\" name=\"teacher\"> <input type=\"submit\" value=\"Go!\"></form>";
echo "</div>";


echo "<div class=\"block\">";
$res = mysql_query("SELECT * FROM `teachers`");
if(mysql_num_rows($res) == 0) {
    echo "No teacher found in the database";
}
else {
echo "<ul>";
while($row = mysql_fetch_assoc($res)) {
    echo "<li>" . $row['teacher_name'] . "</li>";
}
echo "</ul>";
}
echo "</div>";
?>
</div>
</body>
</html>