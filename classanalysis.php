<?php
include("connect.php");
include("functions.lib.php");
$classId = $_GET['class'];
$examId  = $_GET['exam'];

//
// calculating the centum scorers
//
$query = "SELECT `students`.`student_name`,`coursecode`.`course_name` FROM `marks`,`coursecode`,`students`,`subjects` WHERE `marks`.`marks` = '100' AND `marks`.`exam_id` = '{$examId}' AND `marks`.`student_id` = `students`.`student_id` AND `coursecode`.`course_code` = `subjects`.`course_id` AND `subjects`.`subject_id` = `marks`.`subject_id` AND `marks`.`student_id` IN (SELECT `student_id` FROM `students` WHERE `class_id` = '{$classId}')";
//echo $query;
$res = mysql_query($query);
if(mysql_num_rows($res) == 0) {

}
else {
    echo "Centum Scorers from " . getClassName($classId) . " in " . getExamName($examId) . ":<ul>";
    while($row = mysql_fetch_assoc($res)) {
        echo "<li><b>" . $row['student_name'] ."</b> in " . $row['course_name'] . "</li>";
    }
    echo "</ul>";
}

?>