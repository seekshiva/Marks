<?php include("functions.lib.php"); ?>
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
else if(isset($_GET['class']) && isset($_GET['exam'])) {
    if(isset($_GET['editmarks'])) editStudentMarks();
    else getStudentsFromClass($_GET['exam']);
}
else if(isset($_GET['class'])) {
if(0) {
    getStudentsFromClass("");
}
else
{

$classId = $_GET['class'];

    echo "<div id=\"options\" class=\"np\">";
    echo "<div id=\"optionHead\">Options</div><br />";
    echo "<div style=\"display:none;\" id=\"optionBody\">";

    echo "<a class=\"s\" href=\"./?addstudents=1&class=" . $_GET['class'] . "\">Add students to this class</a><hr />";
    $curriculum = getClassCurriculum($classId);
    $res = mysql_query("SELECT `course_code`,`course_name` FROM `coursecode` WHERE `curriculum`='" . $curriculum . "'");
    echo "<form action=\"./?subject=add\" method=\"POST\"><span class=\"s\">Add new course for the class:</span> <select name=\"courseId\">";
    echo "<option value=\"0\">-</option>";
    while($row = mysql_fetch_assoc($res)) {
        echo "<option value=\"" . $row['course_code'] . "\">" . $row['course_name'] . "</option>";
    }
    echo "</select><input type=\"hidden\" name=\"classId\" value=\"" . $classId . "\"> <input type=\"submit\" value=\"Go!\">";
    echo "</form><hr />";
    echo "<form class=\"s\" action=\"./?addExam\"><label for=\"examName\">Add a new exam for the class: </label><input type=\"hidden\" name=\"class\" value=\"" . $classId . "\"><input type=\"text\" name=\"examName\" /><input type=\"submit\" value=\"Add\"></form>";

    echo "</div></div>\n\n";


echo "<h3><a href=\"./?class=" . $classId . "\">Class " . getClassName($classId) . "</a><span id=\"examName\"></span></h3>\n<h4 class=\"s\"><span class=\"np\">Curriculum : " . getClassCurriculum($classId) . "</span></h4>\n<h4 class=\"s np\">Class Teacher: " . getClassTeacherLink($classId) . "</h4><br />";

    $examId = 0;
    /**
     *	The part where the list of exams is listed
    **/
    
    echo "<div class=\"block np\"><span class=\"s\">Select an Exam from the list: </span>";
    $res2 = mysql_query("SELECT * FROM `exams` WHERE `class` = '" . getClass($classId) . "';");
    if(mysql_num_rows($res2) == 0) {
        echo "<div style=\"margin:5px; \" class=\"s\">No exams has been conducted for this class. Use the box below to add a new exam to the list.</div>";
    }
    else {
    echo "\n<select id=\"selectExam\" onchange=\"updateExamInfo(" . $_GET['class'] . ", this.value);\">";
    echo "\n<option value=\"0\" selected=\"selected\">--Select an Exam from below--</option>";
    while($row2 = mysql_fetch_assoc($res2)) {
    if($examId && $examId == $row2['exam_id'])
        echo "\n<option selected=\"true\" value=\"" . $row2['exam_id'] . "\">" . $row2['exam_name'] . "</option>";
    else
        echo "\n<option value=\"" . $row2['exam_id'] . "\">" . $row2['exam_name'] . "</option>";
    }
    echo "\n</select>\n\n";
    }
    if($examId == 0) {
    }
    else {
        echo " <a href=\"./classanalysis.php?class=" . $_GET['class'] . "&exam=" . $_GET['exam'] . "\">See analysis of the exam</a>";
    }
    
    echo "</div>";

$str =<<<abc
    <div class="np" style="margin-top:0; margin-left:10px; margin-bottom:5px; font-size:90%; "><span id="listContainer"></span></div>
    <div class="np"><span id="sorter">Sort by: <a href="#" onclick="sortByExamNo(); return false;">Exam No.</a> <a href="#" onclick="sortByRank(); return false;">Rank</a></span></div>
    <div id="marks-div"></div>
    <script>
        $(document).ready(function() {

	    $.getJSON("./ajax.php?class={$_GET['class']}",function(data) {
	        studentsList = data;
		generateStudentMapping();
		getStudentsFromClass();
	    });
	});
    </script>
abc;

echo $str;
include("signatures.php");
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