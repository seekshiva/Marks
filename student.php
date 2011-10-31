<!doctype html>
<html>
<head>
<title>Student Information</title>
<link rel="stylesheet" href="main.css">
</head>
<body>
<div id="menu">
    <a href="./">Home</a><a style="background-color:#e1e1f1; " href="./student.php">Students</a><a href="./teachers.php">Teachers</a>
</div>
<?php
include("connect.php");
include("functions.lib.php");
if(isset($_GET['sid'])) {
   $query = "SELECT `students`.`adm_no`,`students`.`student_id`,`students`.`student_name`,`classes`.`class_id`,`classes`.`class_name`,`teams`.`team_name`,`houses`.`house_name` FROM `students`,`classes`,`teams`,`houses` WHERE `students`.`student_id` = '" . $_GET['sid'] . "' AND `classes`.`class_id` = `students`.`class_id` AND `teams`.`team_id` = `students`.`team_id` AND `houses`.`house_id` = `students`.`house_id`";
   //echo $query;
   $res = mysql_query($query);
   $row = mysql_fetch_assoc($res);
   $classId = $row['class_id'];
   echo "<h3>" . $row['student_name'] . " - Student Info</h3>";
   echo "<table cellpadding='10'><tr><td><img src=\"default.jpg\" height=\"70px\"></td><td>";
   echo "<div style=\"font-sizea:80%; \">";
   echo "Admission Number: " . $row['adm_no'] . "<br>";
   echo "Class " . $row['class_name'] . "<br>";
   echo $row['house_name'] . " House - Team " . $row['team_name'] . "<br>";
   echo "</div></td></tr></table>";
   
   $subjArr = Array();
   $res2 = mysql_query("SELECT `subjects`.`subject_id`,`coursecode`.`course_name` FROM `subjects`,`coursecode` WHERE `subjects`.`class_id` = '" . $row['class_id'] . "' AND `subjects`.`course_id` = `coursecode`.`course_code`");
   while($row2 = mysql_fetch_assoc($res2))
   {
       $subjArr[$row2['subject_id']] = $row2['course_name'];
   }
   
   echo "<br /><table style='text-align:center' border='1' cellpadding='3' cellspacing='0'>\n<tr><th>Examination</th>";
   foreach($subjArr as $key=>$val) echo "<th>" . $val . "</th>";
   echo "<th>Total</th><th>Percentage</th></tr>\n";

   $query = "SELECT `marks`.`student_id`,`marks`.`exam_id`,`exams`.`exam_name`,`marks`.`subject_id`,`marks`.`marks` FROM `marks`,`exams` WHERE `marks`.`exam_id` = `exams`.`exam_id` AND `marks`.`student_id` = '" . $row['student_id'] . "' ORDER BY `marks`.`exam_id` ASC";
   $res = mysql_query($query);
   $marksArr = Array();
   $marksArr["exam_id"] = -1;
   while($row = mysql_fetch_assoc($res)) {
       if($marksArr["exam_id"] != $row['exam_id']) { //new row
           displayMarks($marksArr , $subjArr);
	   $marksArr["exam_id"]   = $row['exam_id'];
	   $marksArr["exam_name"] = $row['exam_name'];
	   $marksArr[$row['subject_id']]      = $row['marks'];
       }
       else {
	   $marksArr[$row['subject_id']] = $row['marks'];
       }
   }
   displayMarks($marksArr , $subjArr);
   
   echo "</table>";
}
else {
    echo "<h3>Student Information Rretrieval System</h3>";
    echo "<form action=\"\" method=\"GET\">Enter Student Id : <input type=\"text\" name=\"sid\" /> <input type=\"submit\" value=\"Go!\"></form><br>";
    echo "Or you could click on the student name from the class or house list, to access information about the student.";
}

?>
</body>
</head>
