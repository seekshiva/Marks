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

if(isset($_GET['teacherid'])) {
    $res = mysql_query("SELECT * FROM `teachers` WHERE `teacher_id` = '" . $_GET['teacherid'] . "'");
    if(mysql_num_rows($res) == 0) {
        header("Location: ./teachers.php");
    }
    else {
        $row = mysql_fetch_assoc($res);
	echo "<table><tr><td>";
	echo "<img height=\"70px\" src=\"./default.jpg\"></td><td style=\"font-size:80%; \"><h3 style=\"margin:0; \">" . $row["teacher_name"] . " (" . $row["teacher_code"] . ")</h3>";
	$res2 = mysql_query("SELECT `class_id`,`class_name` FROM `classes` WHERE `cteacher_id` = '" . $row["teacher_id"] . "'");
	if(mysql_num_rows($res2) > 0) {
	    $row2 = mysql_fetch_assoc($res2);
	    echo "is the class teacher of <a href=\"./?class=" . $row2["class_id"] . "\">" . $row2["class_name"] . "</a><br />";
	}
	$res2 = mysql_query("SELECT `student_id`,`student_name` FROM `students` WHERE `mentor_id` = '" . $row["teacher_id"] . "'");
	if(mysql_num_rows($res2) > 0) {
	    echo "is the personal mentor for <ul style=\"margin:0; \">";
	    while($row2 = mysql_fetch_assoc($res2)) {
	    	echo "<li><a href=\"./student.php?sid=" . $row2["student_id"] . "\">" . $row2["student_name"] . "</a></li>";
	    }
	    echo "</ul>";
	}
	
	
	echo "</td></tr></table>";
    }
}
else {
    $res = mysql_query("SELECT * FROM `teachers`");
    if(mysql_num_rows($res) == 0) {
        echo "No teacher found in the database";
    }
    else {
        echo "List of teachers:";
        echo "<ul>";
    	while($row = mysql_fetch_assoc($res)) {
    	    echo "<li><a href=\"./teachers.php?teacherid=" . $row["teacher_id"] . "\">" . $row['teacher_name'] . " <b>(" . $row["teacher_code"] . ")</b></a></li>";
    	}
    	echo "</ul>";
    }
    echo "<div class=\"block\">";
    echo "<form action=\"\" method=\"GET\"><label class=\"s\" from=\"teacher\">Add New Teacher to the list: </label> <input type=\"text\" name=\"teacher\"> <input type=\"submit\" value=\"Go!\"></form>";
    echo "</div>";
}
?>
</div>
</body>
</html>