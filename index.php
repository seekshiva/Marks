<?php include("functions.lib.php"); ?>
<!doctype html>
<html lang="en">
<head>
<title>JKDSHKJ</title>
<link rel="stylesheet" href="main.css">
<script>
var classId = <?php echo isset($_GET['class'])?$_GET['class']:0; ?>, 
houses = [<?php echo getHousesList(); ?>],
teams = [<?php echo getTeamsList(); ?>];
</script>
<script src="jquery.js"></script>
<script src="script.js"></script>
</head>
<body>
<div id="menu">
    <a <?php if(count($_GET) == 0) echo "id=\"currentMenuItem\"";  ?>href="./">Home</a><a href="./student.php">Students</a><a href="./teachers.php">Teachers</a>
</div>
<div id="wrapper">
<?php
if(isset($_GET['subject'])) {
    if($_GET['subject'] == "add") addSubject();
    if($_GET['subject'] == "del") {
        $res = mysql_query("DELETE FROM `subjects` WHERE `class_id` = " . $_GET['classId'] . " AND `course_id` = '" . $_GET['courseId'] . "' LIMIT 1");
	header("Location: ./?class=" . $_GET['classId']);
    }
} 
else if(isset($_GET['addclass'])) {
    addClass();
}
else if(isset($_GET['addstudents'])) {
    addStudents();
}
else if(isset($_GET['editstudents'])) {
    if(isset($_GET['uids'])) {
        editStudentInfo();
	print_r($_POST);
        echo "<meta http-equiv=\"refresh\" content=\"0;./?class=" . $_GET['class'] . "\">";
    }
    else {
    	getStudentsFromClass();
    }
}
else if(isset($_GET['examName'])) {
    addExam();
}
else if(isset($_GET['class']) && isset($_GET['exam'])) {
    if(isset($_GET['editmarks'])) editStudentMarks();
    else getStudentsFromClass($_GET['exam']);
}
else if(isset($_GET['class'])) {
    getStudentsFromClass("");
}
else if(isset($_GET['team'])) {
    getStudentsFromTeam($_GET['team']);
}
else if(isset($_GET['house'])) {
    getStudentsFromHouse($_GET['house']);
}
else {
    echo "<table cellspacing=\"30\" style=\"vertical-align:top; \"><tr><td>";
    generateClassesList();
    echo "</td><td>";
    generateHousesList();
    echo "</td></tr></table>";
}
?>
</div>
</body>
</html>