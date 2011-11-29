<?php
header("Content-type: application/json");
include("./functions.lib.php");
$classId = isset($_GET['class'])?$_GET['class']:"1";
    $res = mysql_query("SELECT `student_id`, `exam_no`, `adm_no`, `student_name`, `team_id`, `house_id` FROM `students` WHERE `class_id` = '" . $classId . "' ORDER BY `exam_no` ASC");
$str = "{\n";

$count = 1;
while($row = mysql_fetch_assoc($res))
{
$str.=<<<str
    "{$count}": {
    	"sid":"{$row['student_id']}",
    	"name":"{$row['student_name']}",
	"exam_no":"{$row['exam_no']}",
	"adm_no":"{$row['adm_no']}",
	"team":"{$row['team_id']}",
	"house":"{$row['house_id']}"
    },

str;
    $count = $count + 1;
}
$str.=<<<str
    "length":"{$count}"
}
str;
echo $str;
?>