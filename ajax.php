<?php
header("Content-type: application/json");
include("./functions.lib.php");
$classId = isset($_GET['class'])?$_GET['class']:"0";

if(!isset($_GET['exam'])) {
$str = "{\n";

$cname = getClassName($classId);
$cteacher = str_replace('"','\"',getClassTeacherLink($classId));
$curriculum = getClassCurriculum($classId);

$str .=<<<str
    "class":"{$cname}",
    "cteacher":"{$cteacher}",
    "curriculum":"{$curriculum}",

str;

$res = mysql_query("SELECT `student_id`, `exam_no`, `adm_no`, `student_name`, `team_id`, `house_id`, `mentor_id` FROM `students` WHERE `class_id` = '" . $classId . "' ORDER BY `exam_no` ASC");
$count = 1;
while($row = mysql_fetch_assoc($res))
{
$sname = trim($row['student_name']);
$str.=<<<str
    "{$count}": {
    	"sid":"{$row['student_id']}",
    	"name":"{$sname}",
	"exam_no":"{$row['exam_no']}",
	"adm_no":"{$row['adm_no']}",
	"team":"{$row['team_id']}",
	"house":"{$row['house_id']}",
	"mentor":"{$row['mentor_id']}"
    },

str;
    $count = $count + 1;
}
$str.=<<<str
    "length":"{$count}"
}
str;
echo $str;
}

else {
$examId = $_GET['exam'];
$rank = Array();
$thisrank = Array();
$subjectArr = Array();

$str =<<<str
SELECT `students`.`student_id`, `students`.`student_name`,SUM(`marks`.`marks`)/COUNT(`marks`.`marks`) AS `avg`
FROM `students`,`marks`
WHERE `marks`.`marks` != -1
AND `students`.`student_id` = `marks`.`student_id`
AND `marks`.`exam_id` = '{$_GET['exam']}'
AND `students`.`student_id` IN (
   SELECT `student_id` FROM `students` WHERE `class_id` IN (
      SELECT `class_id` FROM `classes` WHERE `class`  = (
         SELECT `class` FROM `classes` WHERE `classes`.`class_id` = '{$_GET['class']}'
      )
   )
)
GROUP BY `students`.`student_id` ORDER BY `avg` DESC;
str;

$res = mysql_query($str);
$count = 1;
while($row = mysql_fetch_assoc($res)) {
$rank[$row['student_id']] = $count;
$count = $count + 1;
}

$res = mysql_query("SELECT `course_id`,`course_name`,`avg_req` FROM `subjects`,`coursecode` WHERE `class_id` = '{$classId}' AND `subjects`.`course_id` = `coursecode`.`course_code` ORDER BY `subject_id` ASC");
$count = 0;
while($row = mysql_fetch_assoc($res)) {
   $subjectArr["code"][$count] = $row["course_id"];
   $subjectArr["name"][$count] = $row["course_name"];
   $subjectArr["avg"][$count] = $row["avg_req"];
   $count = $count + 1;
}

$stuArr = Array();

$res = mysql_query("SELECT `student_id`,`course_code`,`marks` FROM `marks` WHERE `exam_id` = {$examId} AND `student_id` IN (SELECT `student_id` FROM `students` WHERE `class_id` = '{$classId}') ORDER BY `student_id` ASC");
while($row = mysql_fetch_assoc($res)) {
$stuArr[$row['student_id']][$row['course_code']] = $row['marks'];
}

$ename = getExamName($examId);
$emaxmarks = getExamMaxMarks($examId);

$res = mysql_query("SELECT `class_id` FROM `classes` WHERE `class` = '" . getClass($_GET['class']) . "'");
$temp = "";
while($row = mysql_fetch_assoc($res)) $temp .= ", \"" . $row['class_id'] . "\":\"1\"";
$temp = substr($temp,2);

$str =<<<str
{
    "exam_name":"$ename",
    "exam_max_marks":"$emaxmarks",
    "classes":{{$temp}},

str;

$count = 1;
foreach($stuArr as $key=>$val) {
   $str .=<<<str
    "{$key}": {

str;
for($i = 0; $i < count($subjectArr["code"]); $i = $i + 1) {
if(isset($stuArr[$key][$subjectArr["code"][$i]])) $marks = $stuArr[$key][$subjectArr["code"][$i]];
else $marks = "";
if($marks == -1) $marks = "ab";
$comma = ",";//(count($subjectArr["code"])- 1 != $i)?",":"";
$str.=<<<str
        "{$subjectArr["code"][$i]}": "{$marks}"{$comma}

str;
}
$thisrank[$rank[$key]] = $key;
$str.=<<<str
        "rank": "{$rank[$key]}"
    },

str;
   $count = $count + 1;
}

$str .=<<<str
    "subjects" : [

str;

for($i = 0; $i < count($subjectArr["code"]); $i = $i + 1)
if($subjectArr["avg"][$i] == "1") {
   $comma = (count($subjectArr["code"])- 1 != $i && $subjectArr["avg"][$i + 1] != "0")?",":"";
$str .=<<<str
        {"code":"{$subjectArr["code"][$i]}", "name":"{$subjectArr["name"][$i]}"}{$comma}

str;
}


$str .=<<<str
    ],
    "no_avg_subjects" : [

str;

for($i = 0; $i < count($subjectArr["code"]); $i = $i + 1)
if($subjectArr["avg"][$i] == "0") {
   $comma = (count($subjectArr["code"])- 1 != $i && $subjectArr["avg"][$i] == "0")?",":"";
$str .=<<<str
        {"code":"{$subjectArr["code"][$i]}", "name":"{$subjectArr["name"][$i]}"}{$comma}

str;
}


$str .=<<<str
    ],
    "class_avg" : {
str;

for($i = 0; $i < count($subjectArr["code"]); $i = $i + 1) {
   $comma = ($i == "0")?"":",";
$avg = getClassAvg(getClass($classId),$examId,$subjectArr["code"][$i]);
$str .=<<<str
{$comma}
	"{$subjectArr["code"][$i]}": "{$avg}"
str;
}



$c = count($rank);
$rl ="";
for($i = 0; $i <= $c; $i = $i + 1)
if(isset($thisrank[$i])) {
   $rl .= $thisrank[$i] . ",";
}
$rl = substr($rl,0,strlen($rl) - 1);
$str .=<<<str

    },
    "class_total_strength":"$c",
    "ranklist": [$rl]
}

str;



echo $str;
}
?>
