<?php
include("functions.lib.php");
header("Content-type:application/json");
$q = trim($_GET['searchq']);
if($q == "") {
echo "[]";
die;
}
$query = "SELECT * FROM `students` WHERE `student_name` LIKE '{$q}%' OR `student_name` LIKE '% {$q}%'";
$res = mysql_query($query);
$str = "[\n";
$comma = "";
error_log("####### - " . print_r($_GET,true));
error_log("##### num - " . mysql_num_rows($res) . " - " . $q);
while($row = mysql_fetch_assoc($res)) {
$class = "<a href=\\\"./?class=" . $row['class_id'] . "\\\">" . getClassName($row['class_id']) . "</a>";
$house = "<a href=\\\"./?house=" . $row['house_id'] . "\\\">" . getHouseName($row['house_id']) . "</a>";
$team = "<a href=\\\"./?team=" . $row['team_id'] . "\\\">" . getTeamName($row['team_id']) . "</a>";
$str.=<<<str
{$comma} {
    "sid":"{$row['student_id']}",
    "name":"{$row['student_name']}",
    "adm_no":"{$row['adm_no']}",
    "house":"{$house}",
    "team":"{$team}",
    "class":"{$class}"
}
str;
$comma = ",";
}
$str .= "\n]";

echo $str;
?>