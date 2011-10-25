<?php
include("connect.php");

/*
 * initialises the global varibles such as
 */
function init() {}

function getHousesList() {
    $res = mysql_query("SELECT * FROM `houses` WHERE 1 ORDER BY `house_id`");
    $str = "";
    while($row = mysql_fetch_assoc($res)) {
        $str .= "{houseId: \"" . $row['house_id']. "\", house: \"" . $row['house_name'] . "\" }, ";
    }
    return $str;
}

function getTeamsList() {
    $res = mysql_query("SELECT * FROM `teams` WHERE 1 ORDER BY `team_id`");
    $str = "";
    while($row = mysql_fetch_assoc($res)) {
        $str .= "{teamId: \"" . $row['team_id']. "\", team: \"" . $row['team_name'] . "\" }, ";
    }
    return $str;
}

function generateClassesList() {
    echo "<h3>Classes: <span class=\"s\">[<a href=\"./?addclass=1\">Add a new class to the list</a>]</span></h3>";
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

function addStudents() {
    if(isset($_POST['students'])) {
        $a=Array();$b = Array();
        //print_r($_POST);
        $x = explode("\n",$_POST['students']);
	for($i=0;$i<count($x);$i = $i+1) {
	    $c = explode("-",$x[$i]);
	    if(trim($c[0]) != "") $a[] = trim($c[0]);
	    if(trim($c[1]) != "") $b[] = trim($c[1]);
	}
	
	$query = "INSERT INTO `marklist`.`students` (`adm_no`, `student_name`, `class_id`) VALUES ";
	for($i=0;$i<count($a);$i=$i+1) {
	    if($i != 0) $query .= ", ";
	    $query .= "('" . $a[$i] . "', '" . $b[$i] . "','" . $_GET['class'] . "')";
	}
	$query .= ";";
	mysql_query($query);
	header("Location: ./?class=" . $_GET['class']);
    }
    else {
        ?>
	<form action="" method="POST">
	<table>
	    <tr><td><label>Names of students: </label></td><td><textarea name="students" rows="20"></textarea></td></tr>
	    <tr><td><label></label></td><td></td></tr>
	    <tr><td colspan="2" align="center"><input type="submit" value="Add Students"></td></tr>
	</table></form>
	<?php
    }
}



function editStudentInfo() {
    $uid = explode(",",$_GET['uids']);
    for($i=0;$i<count($uid);$i=$i+1) {
        if(isset($_GET['team']) && $_GET['team'] != 0) $query = mysql_query("UPDATE  `students` SET  `team_id` =  '" . $_GET['team'] . "' WHERE  `student_id` =" . $uid[$i] . ";");
	if(isset($_GET['house']) && $_GET['house'])$query = mysql_query("UPDATE  `students` SET  `house_id` =  '" . $_GET['house'] . "' WHERE  `student_id` =" . $uid[$i] . ";");
	//echo $query."<br>";
    	//$res = mysql_query($query);
    }
}

function addClass() {
    if(isset($_POST['class'])) {
        $query = "INSERT INTO `marklist`.`classes` (`class_id`, `class_name`, `curriculum`) VALUES (NULL, '" . $_POST['class'] . "', '" . $_POST['curriculum'] . "');";
        mysql_query($query);
	header("Location: ./");
    }
    else {
    ?>
    <h3>Add a new Class to the database</h3>
    <form action="" method="POST">
    	 <table>
	     <tr><td><label>Enter Name of class<span class="s"> [like <b>XII-A(CS)</b> or <b>IX-B</b>]</span></label></td><td><input type="text" name="class" /></td></tr>
	     <tr><td><label>Curriculum followed</label></td><td>
	         <select name="curriculum">
		     <option value="CBSE">CBSE</option>
		     <option value="Samacheer">Samacheer</option>
	         </select>
	     </td></tr>
	     <tr><td colspan="2"><input type="submit" value="Add Class"></td></tr>
    </form>
<?php
    }

}

function addSubject() {
    if(mysql_num_rows(mysql_query("SELECT * FROM `subjects` WHERE `class_id` = " . $_POST['classId'] . " AND `course_id` = " . $_POST['courseId'] . ";")) == 0 && $_POST['courseId'] != 0) {
        $query = "INSERT INTO `subjects` (`class_id`, `course_id`) VALUES ('" . $_POST['classId'] . "', '" . $_POST['courseId'] . "');";
    	mysql_query($query);
    }
    header("Location: ./?class=" . $_POST['classId']);
}

function addExam() {
    $query = "INSERT INTO `exams` (`class_id`,`exam_name`) VALUES ('" . $_GET['class'] . "','" . $_GET['examName'] . "')";
    mysql_query($query);
    header("Location: ./?class=" . $_GET['class']);
}

function getTeamName($teamId) {
    $row = mysql_fetch_array(mysql_query("SELECT `team_name` FROM `teams` WHERE `team_id` = '" . $teamId . "'"));
    return $row["team_name"];
}

function getHouseName($houseId) {
    $row = mysql_fetch_array(mysql_query("SELECT `house_name` FROM `houses` WHERE `house_id` = '" . $houseId . "'"));
    return $row["house_name"];
}

function getClassName($classId) {
    $row = mysql_fetch_array(mysql_query("SELECT `class_name` FROM `classes` WHERE `class_id` = '" . $classId . "'"));
    return $row["class_name"];
}

function getClassCurriculum($classId) {
    $row = mysql_fetch_array(mysql_query("SELECT `curriculum` FROM `classes` WHERE `class_id` = '" . $classId . "'"));
    return $row["curriculum"];
}

function getStudentsFromClass($examId) {
    $classId = $_GET['class'];
    $subjectArray = Array();
    echo "<h3>Students in class " . getClassName($classId) . " <br /><span class=\"s\">Curriculum : " . getClassCurriculum($classId) . " - <a href=\"./?addstudents=1&class=" . $_GET['class'] . "\">Add students to this class</a></span></h3>";

    /**
     *   The part where the list of courses taught in the class is listed
    **/
    
    echo "<div class=\"block\">";
    $res = mysql_query("SELECT `coursecode`.`course_code` AS `code`,`coursecode`.`course_name` AS `name` FROM `subjects`,`coursecode` WHERE `subjects`.`class_id`='" . $classId . "' AND `subjects`.`course_id` = `coursecode`.`course_code`");

    if(mysql_num_rows($res) == 0) {
       echo "No subjects found!";
    }
    else {
    echo "<table style='margin:5px; ' border='1' cellspacing='0' cellpadding='3'><tr>";
    while($row = mysql_fetch_array($res)) {
        $subjectArray[$row['code']] = $row['name'];
        echo "<td>" . $row['name'] . " <a class=\"closeButton\" href=\"./?subject=del&courseId=" .  $row['code']  . "&classId=" . $classId . "\">x</a></td>"; 
    }
    echo "</tr></table>";
    }
    $curriculum = getClassCurriculum($classId);
    $res = mysql_query("SELECT `course_code`,`course_name` FROM `coursecode` WHERE `curriculum`='" . $curriculum . "'");
    echo "<form action=\"./?subject=add\" method=\"POST\"><span class=\"s\">Add new course for the class:</span> <select name=\"courseId\">";
    echo "<option value=\"0\">-</option>";
    while($row = mysql_fetch_assoc($res)) {
        echo "<option value=\"" . $row['course_code'] . "\">" . $row['course_name'] . "</option>";
    }
    echo "</select><input type=\"hidden\" name=\"classId\" value=\"" . $classId . "\"> <input type=\"submit\" value=\"Go!\">";
    echo "</form></div>\n\n";

    /**
     *	The part where the list of exams is listed
    **/
    
    echo "<div class=\"block\">";
    $res = mysql_query("SELECT * FROM `exams` WHERE `class_id` = '" . $classId . "';");
    if(mysql_num_rows($res) == 0) {
        echo "<div style=\"margin:5px; \" class=\"s\">No exams has been conducted for this class. Use the box below to add a new exam to the list.</div>";
    }
    else {
    echo "exam id" . $examId . "<br>\n";
    echo "<select id=\"selectExam\" onchange=\"window.location = './?class=" . $classId . "&exam=' + this.value\">";
    echo "<option value=\"0\">--Select an Exam from below--</option>";
    while($row = mysql_fetch_assoc($res)) {
    if($examId && $examId == $row['exam_id'])
        echo "<option selected=\"true\" value=\"" . $row['exam_id'] . "\">" . $row['exam_name'] . "</option>";
    else
        echo "<option value=\"" . $row['exam_id'] . "\">" . $row['exam_name'] . "</option>";
    }
    echo "</select>";
    }
    echo "<form action=\"./?addExam\"><label for=\"examName\">Add a new exam for the class: </label><input type=\"hidden\" name=\"class\" value=\"" . $classId . "\"><input type=\"text\" name=\"examName\" /><input type=\"submit\" value=\"Add\"></form>";
    echo "</div>";

    $res = mysql_query("SELECT `student_id`, `adm_no`, `student_name`, `team_id`, `house_id` FROM `students` WHERE `class_id` = '" . $classId . "' ORDER BY `student_id`");
    if(mysql_num_rows($res) == 0) {
        echo "<tr><td colspan=\"5\">No student found in the database!</td></tr>";
	return;
    }

    echo "<script> window.onload = function() { filterStudents('editstudents'); }; </script>";

    echo "With the below selected students, set <span id=\"listContainer\"></span>";
    echo "<table id=\"studentsTable\" cellpadding='5' cellspacing='0' border='1'>";
    echo "<tr><th></th><th>Admission Number</th><th>Name</th><th>House</th><th>Team</th>";
    if($examId != "") foreach($subjectArray as $key=>$val) echo "<th>" . $val . "</th>";
    echo "</tr>\n";

    while($row = mysql_fetch_assoc($res)) {
        $query2 = "SELECT `marks` FROM `marks` WHERE `student_id` = '" . $row['student_id'] . "' AND `exam_id` = '" . $examId . "' ";
        $res2 = mysql_query($query2);
        echo "<tr><td onclick=\"this.childNodes[0].checked = !this.childNodes[0].checked; \" style=\"cursor:pointer; \"><input type=\"checkbox\" name=\"studentid[]\" value=\"" . $row['student_id'] . "\" /></td><td>" . $row['adm_no'] . "</td><td>" . $row['student_name'] . "</td><td>" . getHouseName($row['house_id']) . "</td><td>" . getTeamName($row['team_id']) . "</td></tr>\n";
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
    echo "<h3>Students in " . getHouseName($houseId) . " House</h3>";
    $res = mysql_query("SELECT `adm_no`, `student_name`, `class_id` FROM `students` WHERE `house_id` = '" . $houseId . "' ORDER BY `student_id`");
    echo "<table cellpadding='5' cellspacing='0' border='1'>";
    echo "<tr><th>Admission<br> Number</th><th>Name</th><th>Class</th></tr>";
    while($row = mysql_fetch_assoc($res)) {
        echo "<tr><td>" . $row['adm_no'] . "</td><td>" . $row['student_name'] . "</td><td>" . getClassName($row['class_id']) . "</td></tr>";
    }
    echo "</table>";
}
?>
