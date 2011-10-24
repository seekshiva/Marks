<?php
include("connect.php");

/*
 * initialises the global varibles such as
 */
function init() {}

function generateClassesList() {
    echo "<h3>Classes:</h3>";
    $res = mysql_query("SELECT * FROM `classes` WHERE 1 ORDER BY `class_id`");
    echo "<ul>";
    while($row = mysql_fetch_assoc($res)) {
        echo  "<li><a href=\"./?class=" . $row['class_id']. "\">" . $row['class_name'] . "</a></li>";
    }
    echo "</ul>";
}

function generateTeamsList() {
    echo "<h3>Teams:</h3>";
    $res = mysql_query("SELECT * FROM `teams` WHERE 1 ORDER BY `team_id`");
    echo "<ul>";
    while($row = mysql_fetch_assoc($res)) {
        echo  "<li><a href=\"./?team=" . $row['team_id']. "\">" . $row['team_name'] . "</a></li>";
    }
    echo "</ul>";
}

function generateHousesList() {
    echo "<h3>Houses:</h3>";
    $res = mysql_query("SELECT * FROM `houses` WHERE 1 ORDER BY `house_id`");
    echo "<ul>";
    while($row = mysql_fetch_assoc($res)) {
        echo  "<li><a href=\"./?house=" . $row['house_id']. "\">" . $row['house_name'] . "</a></li>";
    }
    echo "</ul>";
}

function getTeamName($teamId) {
    $row = mysql_fetch_array(mysql_query("SELECT `team_name` FROM `teams` WHERE `team_id` = '" . $teamId . "'"));
    return $row["team_name"];
}

function getClassName($classId) {
    $row = mysql_fetch_array(mysql_query("SELECT `class_name` FROM `classes` WHERE `class_id` = '" . $classId . "'"));
    return $row["class_name"];
}

function getStudentsFromClass($classId) {
    echo "<h3>Students in class " . getClassName($classId) . "</h3>";
    $res = mysql_query("SELECT `adm_no`, `student_name`, `team_id` FROM `students` WHERE `class_id` = '" . $classId . "' ORDER BY `student_id`");
    echo "<table cellpadding='5' cellspacing='0' border='1'>";
    echo "<tr><th>Admission Number</th><th>Name</th><th>Team</th></tr>";
    while($row = mysql_fetch_assoc($res)) {
        echo "<tr><td>" . $row['adm_no'] . "</td><td>" . $row['student_name'] . "</td><td>" . getTeamName($row['team_id']) . "</td></tr>";
    }
    echo "</table>";
}

function getStudentsFromTeam($teamId) {
    echo "<h3>Students in team " . getTeamName($teamId) . "</h3>";
    $res = mysql_query("SELECT `adm_no`, `student_name`, `class_id` FROM `students` WHERE `team_id` = '" . $teamId . "' ORDER BY `student_id`");
    echo "<table cellpadding='5' cellspacing='0' border='1'>";
    echo "<tr><th>Admission Number</th><th>Name</th><th>Class</th></tr>";
    while($row = mysql_fetch_assoc($res)) {
        echo "<tr><td>" . $row['adm_no'] . "</td><td>" . $row['student_name'] . "</td><td>" . getClassName($row['class_id']) . "</td></tr>";
    }
    echo "</table>";
}

function getStudentsFromHouse($houseId) {
    echo "<h3>Students in " . getTeamName($teamId) . " House</h3>";
    $res = mysql_query("SELECT `adm_no`, `student_name`, `class_id` FROM `students` WHERE `house_id` = '" . $houseId . "' ORDER BY `student_id`");
    echo "<table cellpadding='5' cellspacing='0' border='1'>";
    echo "<tr><th>Admission Number</th><th>Name</th><th>Class</th></tr>";
    while($row = mysql_fetch_assoc($res)) {
        echo "<tr><td>" . $row['adm_no'] . "</td><td>" . $row['student_name'] . "</td><td>" . getClassName($row['class_id']) . "</td></tr>";
    }
    echo "</table>";
}
?>
