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
        $admNo = explode("\n",$_POST['admissonNo']);
        $examNo = explode("\n",$_POST['examNo']);
        $studentNo = explode("\n",$_POST['students']);
	
	$query = "INSERT INTO `students` (`adm_no`, `exam_no`, `student_name`, `class_id`) VALUES ";
	for($i=0;$i<count($studentNo);$i=$i+1) {
	    if($i != 0) $query .= ", ";
	    $query .= "('" . $admNo[$i] . "', '" . $examNo[$i] . "', '" . $studentNo[$i] . "','" . $_GET['class'] . "')";
	}
	$query .= ";";
	mysql_query($query);
	header("Location: ./?class=" . $_GET['class']);
    }
    else {
        ?>
	<form action="" method="POST">
	<table style="margin:20px; " cellspacing="0">
	    <tr style="text-align:center; font-size:80%; "><td><label>Admission<br>Number</label></td><td><label>Exam Id</label></td><td><label>Names of students</label></td></tr>
	    <tr><td><textarea name="admissonNo" rows="30" cols="5"></textarea></td><td><textarea name="examNo" rows="30" cols="5"></textarea></td><td><textarea name="students" rows="30"></textarea></td></tr>
	    <tr><td><label></label></td><td></td></tr>
	    <tr><td colspan="3" align="center"><input type="submit" value="Add Students"></td></tr>
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
        $query = "INSERT INTO `classes` (`class_id`, `class_name`, `curriculum`, `cteacher_id`) VALUES (NULL, '" . $_POST['class'] . "', '" . $_POST['curriculum'] . "', '" . $_POST['cteacher'] . "');";
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
	     <tr><td>Class Teacher</td><td><select name="cteacher">
	     <?php 
	         $res = mysql_query("SELECT `teacher_id`,`teacher_name` FROM `teachers`");
		 while($row = mysql_fetch_assoc($res))
	         echo "<option value=\"" . $row['teacher_id'] . "\">" . $row['teacher_name'] . "</option>\n";
	     ?>
	     </select></td></tr>
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

function getExamName($examId) {
    $row = mysql_fetch_array(mysql_query("SELECT `exam_name` FROM `exams` WHERE `exam_id` = '" . $examId . "'"));
    return $row["exam_name"];
}

function getSubjectName($subjectId) {
    $row = mysql_fetch_array(mysql_query("SELECT `coursecode`.`course_name` FROM `coursecode`,`subjects` WHERE `subjects`.`subject_id` = '" . $subjectId . "' AND `coursecode`.`course_code` = `subjects`.`course_id`"));
    return $row["course_name"];
}

function getClassCurriculum($classId) {
    $row = mysql_fetch_array(mysql_query("SELECT `curriculum` FROM `classes` WHERE `class_id` = '" . $classId . "'"));
    return $row["curriculum"];
}

function getMentorName($studentId) {
    $row = mysql_fetch_array(mysql_query("SELECT `teachers`.`teacher_id`, `teachers`.`teacher_name` FROM `students`,`teachers` WHERE `students`.`student_id` = '" . $studentId . "' AND `students`.`mentor_id` = `teachers`.`teacher_id`"));
    return ($row["teacher_name"])?"<a href=\"./teachers.php?teacherid=" . $row['teacher_id'] . "\">" . $row["teacher_name"] . "</a>":"not set";
}

function getClassTeacher($classId) {
    $row = mysql_fetch_array(mysql_query("SELECT `teachers`.`teacher_name` FROM `classes`,`teachers` WHERE `classes`.`class_id` = '" . $classId . "' AND `classes`.`cteacher_id` = `teachers`.`teacher_id`"));
    return ($row["teacher_name"])?$row["teacher_name"]:"not set";
}

function getClassTeacherLink($classId) {
    $row = mysql_fetch_array(mysql_query("SELECT `teachers`.`teacher_id`, `teachers`.`teacher_name` FROM `classes`,`teachers` WHERE `classes`.`class_id` = '" . $classId . "' AND `classes`.`cteacher_id` = `teachers`.`teacher_id`"));
    return ($row["teacher_name"])? "<a href=\"./teachers.php?teacherid=" . $row['teacher_id'] . "\">" . $row["teacher_name"] . "</a>":"not set";
}

function editStudentMarks() {
    $classId   = $_GET['class'];
    $examId    = $_GET['exam'];
    $subjectId = $_GET['editmarks'];
    $marksArr  = Array();
    
    if(isset($_POST['dummy'])) {
    
	$str = "";
	$res = mysql_query("SELECT `students`.`student_id` FROM `students`,`marks` WHERE `students`.`class_id` = '{$classId}' AND `students`.`student_id` = `marks`.`student_id` AND `marks`.`exam_id` = '{$examId}' AND `marks`.`course_code` = '{$subjectId}'");
    	while($row = mysql_fetch_assoc($res)) {
	    $str .= ",{" . $row['student_id'] . "}";
	}
	foreach($_POST as $key=>$val)
	if(is_int($key)) {
	    if($str=="" || !strpos(".".$str,"{" . $key . "}")) {
	        mysql_query("INSERT INTO `marks` (`student_id`, `exam_id`, `course_code`, `marks`) VALUES ('{$key}', '{$examId}', '{$subjectId}', '{$val}')");
	    }
	    else {
	        $query = "UPDATE  `marks` SET  `marks` =  '{$val}' WHERE  `student_id` = '{$key}' AND `exam_id` = '{$examId}' AND `course_code` = '{$subjectId}';";
	        mysql_query($query);
	    }
	}
    
	header("Location: ./?class=" . $classId . "&exam=" . $examId);
        return;
    }

    $query = "SELECT `student_id`,`marks` FROM `marks` WHERE `exam_id` = '{$examId}' AND `course_code` = '{$subjectId}' AND `student_id` IN (SELECT `student_id` FROM `students` WHERE `class_id` = '{$classId}')";
    $res = mysql_query($query);
    while($row = mysql_fetch_assoc($res)) {
        $marksArr[$row['student_id']] = $row['marks'];
    }
    
    $res = mysql_query("SELECT `adm_no`,`exam_no`,`student_id`,`student_name` FROM `students` WHERE `class_id` = '" . $classId . "' ORDER BY `exam_no` ASC");
    echo "<style>input[type='text'] {background-color:#dde; border:#fff; padding:3px;  }</style>";
    echo "<form action=\"\" method=\"POST\">";
    echo "<table border='1' cellspacing='0'>\n";
    echo "<tr><th>Exam<br>Number</th><th>Admission<br>Number</th><th>Name</th><th>Marks</th></tr>\n";
    while($row = mysql_fetch_assoc($res)) {
        echo "<tr><td>" .$row['exam_no'] . "</td><td>" .$row['adm_no'] . "</td><td>" . $row['student_name'] . "</td><td><input type=\"text\" name=\"" . $row['student_id'] . "\" value=\"" . (isset($marksArr[$row['student_id']])? $marksArr[$row['student_id']] : "0")  . "\" ></td></tr>\n";
    }
    echo "</table><input type=\"hidden\" name=\"dummy\" value=\"1\"><input type=\"submit\" value=\"Add Marks\" /></form>";
}

function getStudentsFromClass($examId) {
    $classId = $_GET['class'];
    $subjectArray = Array();

    echo "<div id=\"options\">";
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

    echo "<h3><a href=\"./?class=" . $classId . "\">Class " . getClassName($classId) . "</a><br /><span class=\"s\">Curriculum : " . getClassCurriculum($classId) . "<br />Class Teacher: " . getClassTeacherLink($classId) . "<br /></span></h3>";

    
    /**
     *   The part where the list of courses taught in the class is listed
    **/
    
    $res = mysql_query("SELECT `coursecode`.`course_code` AS `code`,`coursecode`.`course_name` AS `name` FROM `subjects`,`coursecode` WHERE `subjects`.`class_id`='" . $classId . "' AND `subjects`.`course_id` = `coursecode`.`course_code`");
    if(mysql_num_rows($res) == 0) {
        echo "No subjects found!";
    }
    else {
        echo "<table id=\"subjList\" border='1' cellspacing='0' cellpadding='3'><tr>";
    	while($row = mysql_fetch_array($res)) {
            $subjectArray[$row['code']] = $row['name'];
            echo "<td>" . $row['name'] . " <a class=\"closeButton\" href=\"./?subject=del&courseId=" .  $row['code']  . "&classId=" . $classId . "\">x</a></td>"; 
    	}
    	echo "</tr></table>";
    }


    $res = mysql_query("SELECT `student_id`, `exam_no`, `adm_no`, `student_name`, `team_id`, `house_id` FROM `students` WHERE `class_id` = '" . $classId . "' ORDER BY `exam_no` ASC");
    if(mysql_num_rows($res) == 0) {
        echo "<tr><td colspan=\"5\">No student found in the database!</td></tr>";
	return;
    }

    echo "<script> window.onload = function() { setHouseInfo(); }; </script>";
    
    /**
     *	The part where the list of exams is listed
    **/
    
    echo "<div class=\"block\">";
    $res2 = mysql_query("SELECT * FROM `exams` WHERE `class_id` = '" . $classId . "';");
    if(mysql_num_rows($res2) == 0) {
        echo "<div style=\"margin:5px; \" class=\"s\">No exams has been conducted for this class. Use the box below to add a new exam to the list.</div>";
    }
    else {
    echo "<select id=\"selectExam\" onchange=\"window.location = './?class=" . $classId . "&exam=' + this.value\">";
    echo "<option value=\"0\">--Select an Exam from below--</option>";
    while($row2 = mysql_fetch_assoc($res2)) {
    if($examId && $examId == $row2['exam_id'])
        echo "<option selected=\"true\" value=\"" . $row2['exam_id'] . "\">" . $row2['exam_name'] . "</option>";
    else
        echo "<option value=\"" . $row2['exam_id'] . "\">" . $row2['exam_name'] . "</option>";
    }
    echo "</select>";
    }
    if($examId == 0) {
    }
    else {
        echo " <a href=\"./classanalysis.php?class=" . $_GET['class'] . "&exam=" . $_GET['exam'] . "\">See analysis of the exam</a>";
    }
    
    echo "</div>";
    echo "<div style=\"margin:20px; margin-left:45px; margin-bottom:5px; font-size:90%; \">With the below selected students, set <span id=\"listContainer\"></span></div>";

    
    echo "<table id=\"studentsTable\" style=\"margin:0; padding:0; \" cellpadding='5' cellspacing='0' border='1'>";
    echo "<tr><th></th><th>Exam No</th><th>Admission<br>Number</th><th>Name</th>";
    if($examId != 0) {
        foreach($subjectArray as $key=>$val) echo "<th><a href=\"./?class={$classId}&exam={$examId}&editmarks=" . $key . "\">" . $val . "</a></th>";
	echo "<th>Total</th><th>Average</th><th>Pass/Fail Status</th>";
    }
    else echo "<th>Mentor</th><th>House</th><th>Team</th>";
    echo "</tr>\n";

    while($row = mysql_fetch_assoc($res)) {
        $subjArr = Array();
        $query2 = "SELECT `course_code`,`marks` FROM `marks` WHERE `student_id` = '" . $row['student_id'] . "' AND `exam_id` = '" . $examId . "' ";
	$res2 = mysql_query($query2);
        while($row2 = mysql_fetch_assoc($res2))
	{
	    $subjArr[$row2['course_code']] = $row2['marks'];
	}
        echo "<tr><td onclick=\"this.childNodes[0].checked = !this.childNodes[0].checked; \" style=\"cursor:pointer; \"><a></a><input type=\"checkbox\" name=\"uids[]\" value=\"" . $row['student_id'] . "\" /></td><td>" . $row['exam_no'] . "</td><td>" . $row['adm_no'] . "</td><td><a href=\"./student.php?sid=" . $row['student_id'] . "\">" . $row['student_name'] . "</a></td>";
	if($examId == 0) {
	    echo "<td>" . getMentorName($row["student_id"]) . "</td><td>" . getHouseName($row['house_id']) . "</td><td>" . getTeamName($row['team_id']) . "</td>";
	}
	else {
	    $count = 0;
	    $sum   = 0;
	    $fail  = 0;
	    foreach($subjectArray as $key=>$val ) {
	        echo "<td";
	    	if(isset($subjArr[$key])) {
		    if($subjArr[$key] < 35) echo " style=\"background-color:#a00; color:#fff; \"";
	            echo ">" . $subjArr[$key];
		    if($subjArr[$key] < 35 ) $fail = 1;
		    $count = $count + 1;
		    $sum = $sum + $subjArr[$key];
	    	}
	    	else {
	            echo ">not available<br />";
	    	}
	    	echo "</td>";
	    }
	    $avg = ($count) ? $sum/$count : 0;
	    $avg = substr($avg,0,strpos($avg,".") + 3);
	    echo "<td>" . $sum . "</td><td>" . $avg . "</td><td>";
	    if($fail) echo "FAIL"; else echo "PASS";
	    echo "</td>";
	}
	echo "</tr>";
    }
    echo "</table>";
}

function getStudentsFromHouse($houseId) {
    $houseId = $_GET['house'];
    $classArr = Array();
    if($houseId == 0) {
        header("Location: ./");
    }
    if(isset($_POST['dummyvar'])) {
        echo "<h3>" . getHouseName($_GET['house']) . " House</h3>";
        $res = mysql_query("SELECT `students`.`class_id`,`classes`.`class_name` FROM `students`,`classes` WHERE `students`.`house_id` = '{$houseId}' AND `classes`.`class_id` = `students`.`class_id` GROUP BY `students`.`class_id`");
	while($row = mysql_fetch_assoc($res)) {
	    if(! isset($_POST['class' . $row['class_id']])) continue;
            $subjArr = Array();		      
            $query2 = "SELECT DISTINCT(`course_code`) FROM `marks` WHERE `exam_id` = '" . $_POST['class' . $row['class_id']] . "' ";
	    $res2 = mysql_query($query2);
            while($row2 = mysql_fetch_assoc($res2))
	    {
	        $subjArr[] = $row2['course_code'];
	    }
            $marksArr = Array();
	    echo "<h4>" . $row['class_name'] . " - " . getExamName($_POST['class' . $row['class_id']]) . "</h4>";
	    //echo $_POST['class' . $row['class_id']];

	    
	    echo "<table border='1' cellspacing='0' cellpadding='3'>\n<tr><th>Admission<br>Number</th><th>Name</th>\n\n";
	    foreach($subjArr as $key=>$val) echo "<th>" . getSubjectName($val) . "</th>";
	    echo "<th>Total</th><th>Average</th></tr>\n\n";
	    
	    $query2 = "SELECT `students`.`student_id`, `students`.`adm_no`, `students`.`student_name`, `marks`.`course_code`, `marks`.`marks` FROM `students`,`marks` WHERE `marks`.`exam_id` = '" . $_POST['class' . $row['class_id']] . "' AND `students`.`house_id` = '{$houseId}' AND `students`.`student_id` = `marks`.`student_id` ORDER BY `students`.`student_id`";
	    $res2 = mysql_query($query2);
	    while($row2 = mysql_fetch_assoc($res2)) {
	        $marksArr[$row2['student_id']]["adm_no"] = $row2['adm_no'];
	        $marksArr[$row2['student_id']]["name"] = $row2['student_name'];
	        $marksArr[$row2['student_id']]["subject" . $row2['course_code']] = $row2['marks'];
	    }
	    foreach($marksArr as $key=>$val) {
	        echo "<tr><td>" . $val["adm_no"] . "</td><td>" . $val["name"] . "</td>";
		$sum = 0;
		$count = 0;
		foreach($subjArr as $key2=>$val2) {
		    echo "<td>" . $val["subject" . $val2 ] . "</td>";
		    $count = $count + 1;
		    $sum = $sum + $val["subject" . $val2 ];
		}
		$avg = $count? $sum/$count : 0;
    		$avg = substr($avg,0,strpos($avg,".") + 3);
    		echo "<td>{$sum}</td><td>{$avg}</td></tr>";
	    }
	    echo "</table>";

	}
        return;
    }
    $res = mysql_query("SELECT `students`.`class_id`,`classes`.`class_name` FROM `students`,`classes` WHERE `students`.`house_id` = '{$houseId}' AND `classes`.`class_id` = `students`.`class_id` GROUP BY `students`.`class_id`");
    $num = mysql_num_rows($res);
    if($num == 0) {
        echo "No student was found belonging to this class. Find the corresponding student in the class list and set house of the student there.";
    }
    else {
    echo "<p>Students from <b>" . getHouseName($houseId) . " house</b> were found to be from ";
    $flag = 0;
    while($row = mysql_fetch_assoc($res)) {
        if($flag) echo ", ";
	else $flag = 1;
        echo $row['class_name'];
	$classArr[$row['class_id']] = $row['class_name'];
    }
    echo ".</p>\n";
    echo "Select the examination for each class from which the mark list of students have to be displayed:<br>\n";
    echo "<form action=\"\" method=\"POST\"><table style='margin:10px; '>\n";
    foreach($classArr as $key=> $val) {
        echo "<tr><td>" . $val . "</td><td>";
        $classId = $key;
        $res = mysql_query("SELECT * FROM `exams` WHERE `class_id` = '" . $classId . "';");
        if(mysql_num_rows($res) == 0) {
            echo "<div style=\"margin:5px; \" class=\"s\">No exams has been conducted for this class. Use the box below to add a new exam to the list.</div>";
        }
    	else {
	    echo "<select name=\"class{$key}\">\n";
    	    echo "\t<option value=\"0\">--Select an Exam from below--</option>\n";
    	    while($row = mysql_fetch_assoc($res)) {
        	echo "\t<option value=\"" . $row['exam_id'] . "\">" . $row['exam_name'] . "</option>\n";
	    }
    	    echo "</select>";
        }
     	echo "</td></tr>\n";
    }
    echo "<tr><td colspan=\"2\" align=\"Center\">\n<input type=\"hidden\" name=\"dummyvar\" value=\"1\"><input type=\"submit\" value=\"Go!\"></td></tr>\n</table></form>\n";
    
    
    $res = mysql_query("SELECT `students`.`adm_no`,`students`.`student_id`,`students`.`student_name`,`classes`.`class_name` FROM `students`,`classes` WHERE `students`.`house_id` = '{$houseId}' AND `classes`.`class_id` = `students`.`class_id` GROUP BY `students`.`class_id`");
    if(mysql_num_rows($res) ==0) return;
    echo "<h3>List of students in this house</h3>";
    echo "<table border='1' cellspacing='0' cellpadding='4'><tr><th>Admission Number</th><th>Student Name</th><th>Class</th></tr>";
    while($row = mysql_fetch_assoc($res)) {
        echo "<tr><td>" . $row['adm_no'] . "</td><td><a href=\"./student.php?sid=" . $row['student_id'] . "\">" . $row['student_name'] . "</a></td><td>" . $row['class_name'] . "</td></tr>";
    }
    echo "</table>";
    }
}

function displayMarks($marksArr , $subjArr) {
    if($marksArr["exam_id"] == -1) return;
    $count = 0;
    $sum   = 0;
    echo "<tr><td>" . $marksArr['exam_name'] . "</td>";
    foreach($subjArr as $key=>$val) {
        if(!isset($marksArr[$key])) {
	    echo "<td>-</td>";
	    continue;
	}
	else echo "<td>" . $marksArr[$key] . "</td>";
        $count = $count + 1;
        $sum = $sum + $marksArr[$key];
    }
    $avg = $count ? $sum/$count : 0;
    $avg = substr($avg,0,strpos($avg,".") + 3);
    echo "<td>{$sum}</td><td>{$avg}</td></tr>";
}
?>
