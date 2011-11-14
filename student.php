<?php
include("connect.php");
include("functions.lib.php");
?>
<!doctype html>
<html>
<head>
<title>Student Information</title>
<link rel="stylesheet" href="main.css">
</head>
<body>
<?php getMenu(2); ?>
<div id="wrapper">
<?php

if(isset($_GET['sid'])) {
   $query = "SELECT `students`.`adm_no`,`students`.`student_id`,`students`.`student_name`,`students`.`house_id`,`classes`.`class_id`,`classes`.`class_name`,`teams`.`team_name`,`houses`.`house_name` FROM `students`,`classes`,`teams`,`houses` WHERE `students`.`student_id` = '" . $_GET['sid'] . "' AND `classes`.`class_id` = `students`.`class_id` AND `teams`.`team_id` = `students`.`team_id` AND `houses`.`house_id` = `students`.`house_id`";
   //echo $query;
   $res = mysql_query($query);
   if(mysql_num_rows($res) == 0) die("Not a valid student id or the house and team info about the student has not been listed yet!");
   $row = mysql_fetch_assoc($res);
   
   if($row['house_id'] == 0)
       $genderPrefix = "Their";
   else if($row['house_id'] <= 12)
       $genderPrefix = "His";
   else
       $genderPrefix = "Her";
   $classId = $row['class_id'];
   echo "<table cellpadding='10'><tr><td><img src=\"default.jpg\" height=\"70px\"></td><td>";
   echo "<h3 style=\"margin:0;\">" . $row['student_name'] . "</h3>";
   echo "<div style=\"font-size:70%; \">";
   echo "is from class <b><a href=\"./?class=" . $row['class_id'] . "\">" . $row['class_name'] . "</a></b>.<br />";
   echo $genderPrefix . " Class Teacher is <b>" . getClassTeacherLink($row['class_id']) . "</b> and " . $genderPrefix . " personal mentor is <b>" . getMentorName($row['student_id']) . "</b>.<br />";
   echo "Admission Number: <b>" . $row['adm_no'] . "</b><br />";
   echo "<b>" . $row['house_name'] . "</b> House<br />Team <b>" . $row['team_name'] . "</b><br />";
   echo "</div></td></tr></table>";
   
   $subjArr = Array();
   $res2 = mysql_query("SELECT `coursecode`.`course_code`,`coursecode`.`course_name` FROM `subjects`,`coursecode` WHERE `subjects`.`class_id` = '" . $row['class_id'] . "' AND `subjects`.`course_id` = `coursecode`.`course_code`");
   while($row2 = mysql_fetch_assoc($res2))
   {
       $subjArr[$row2['course_code']] = $row2['course_name'];
   }
   
   $query = "SELECT `marks`.`student_id`,`marks`.`exam_id`,`exams`.`exam_name`,`marks`.`course_code`,`marks`.`marks` FROM `marks`,`exams` WHERE `marks`.`exam_id` = `exams`.`exam_id` AND `marks`.`student_id` = '" . $row['student_id'] . "' ORDER BY `marks`.`exam_id` ASC";
   $res = mysql_query($query);
   if(mysql_num_rows($res) == 0) die("The student has not written any exam yet!");

   echo "<br /><table style='text-align:center' border='1' cellpadding='3' cellspacing='0'>\n<tr><th>Examination</th>";
   foreach($subjArr as $key=>$val) echo "<th>" . $val . "</th>";
   echo "<th>Total</th><th>Percentage</th></tr>\n";

   $marksArr = Array();
   $marksArr["exam_id"] = -1;
   while($row = mysql_fetch_assoc($res)) {
       if($marksArr["exam_id"] != $row['exam_id']) { //new row
           displayMarks($marksArr , $subjArr);
	   $marksArr["exam_id"]   = $row['exam_id'];
	   $marksArr["exam_name"] = $row['exam_name'];
	   $marksArr[$row['course_code']]      = $row['marks'];
       }
       else {
	   $marksArr[$row['course_code']] = $row['marks'];
       }
   }
   displayMarks($marksArr , $subjArr);
   
   echo "</table>";
}
else {
    echo "<h3>Student Information Retrieval System</h3>";
    echo "<form action=\"\" method=\"GET\">Enter Student Id : <input type=\"text\" name=\"sid\" /> <input type=\"submit\" value=\"Go!\"></form><br>";
    echo "Or select a student from the class list displayed below";
    echo "<h3>Classes:</h3>";
    generateClassesList();
}

?>
</div>
</body>
</head>
