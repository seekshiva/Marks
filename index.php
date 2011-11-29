<?php include("functions.lib.php"); ?>
<!doctype html>
<html lang="en">
<head>
<title>Some Random Title</title>
<link rel="stylesheet" href="main.css">
<script>
var classId = <?php echo isset($_GET['class'])?$_GET['class']:0; ?>, 
houses = <?php echo getHousesList(); ?>,
teams = <?php echo getTeamsList(); ?>,
studentsList;
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
else if(isset($_GET['class']) && isset($_GET['exam'])) {
    if(isset($_GET['editmarks'])) editStudentMarks();
    else getStudentsFromClass($_GET['exam']);
}
else if(isset($_GET['class'])) {
if(1) {
    getStudentsFromClass("");
}
else
{$str =<<<abc
    <div style="margin:20px; margin-left:10px; margin-bottom:5px; font-size:90%; "><span id="listContainer"></span></div>
    <div id="marks-div"></div>
    <script>
        $(document).ready(function() {

	    $.getJSON("./ajax.php?class={$_GET['class']}",function(data) {
	        studentsList = data;
		getStudentsFromClass();
	    });
	});
    </script>
abc;


echo $str;
}
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