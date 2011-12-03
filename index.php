<?php
//if(count($_GET) == 0) header("Location: ./student.php");
include("functions.lib.php");
if(isset($_GET['class']) && !isset($_GET['editmarks']) && !isset($_GET['addstudents'])) {
if(isset($_GET['exam'])) header("Location: ./#!class:" . $_GET['class'] . "|exam:" . $_GET['exam'] . "|list");
else header("Location: ./#!class:" . $_GET['class']);
}
?>
<!doctype html>
<html lang="en">
<head>
<title>Marks App by V. Shiva Nandan</title>
<alink rel="stylesheet" href="main.css">
<link rel="stylesheet" media="screen" href="main.css">
<link rel="stylesheet" media="print" href="print.css">
<script>
var classId = <?php echo isset($_GET['class'])?$_GET['class']:0; ?>, 
houses = <?php echo getHousesList(); ?>,
teams = <?php echo getTeamsList(); ?>,
teachers = <?php echo getTeachersList(); ?>,
studentsList,studentMapping = {};
</script>
<script src="jquery.js"></script>
<script src="script.js"></script>
</head>
<body>
<?php getMenu(1); ?>
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
else if(isset($_GET['class'])) {
    if(isset($_GET['editmarks'])) {
        echo "<a href=\"?class=" . $_GET['class'] . "&exam=" . $_GET['exam'] . "\">Back</a><br /><br />";
        editStudentMarks();
    }
    else getStudentsFromClass(isset($_GET['exam'])?$_GET['exam']:"");
}
else if(isset($_GET['team'])) {
    getStudentsFromTeam($_GET['team']);
}
else if(isset($_GET['house'])) {
    getStudentsFromHouse($_GET['house']);
}
else {
    echo "<div align=\"center\"><h3 id=\"frameset\">";
    echo "<span id=\"f1\">Classes</span> ";
    echo "<span id=\"f2\">Houses</span> ";
    echo "<span id=\"f3\">Teams</span></h3></div>";
    echo "<div class=\"blocklist\"><div id=\"frames\">";
    echo "<div class=\"framevals\" id=\"frameval1\" style=\"display:none;\">";
    generateClassesList();
    echo "</div><div class=\"framevals\" id=\"frameval2\">";
    generateHousesList();
    echo "</div><div class=\"framevals\" id=\"frameval3\" style=\"display:none;\">";
    generateTeamsList();
    echo "</div></div></div>";
}
?>
</div>
</body>
</html>