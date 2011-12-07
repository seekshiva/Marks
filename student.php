<?php
include("functions.lib.php");
?>
<!doctype html>
<html>
<head>
<title>Student Information</title>
<link rel="stylesheet" href="main.css">
<script src="jquery.js"></script>
</head>
<body>
<?php getMenu(2); ?>
<div id="wrapper">
<?php

if(isset($_GET['sid'])) {
   $query = "SELECT `adm_no`,`student_id`,`student_name`,`house_id`,`class_id`,`team_id`,`house_id` FROM `students` WHERE `student_id` = '" . $_GET['sid'] . "'";
   //echo $query;
   $res = mysql_query($query);
   if(mysql_num_rows($res) == 0) die("Not a valid student id or the house and team info about the student has not been listed yet!");
   $row = mysql_fetch_assoc($res);
   
   if($row['house_id'] == 0)
       $genderPrefix = "Their";
   else if($row['house_id'] <= 12)
       $genderPrefix = "His";
   else
       $genderPrefix = "Her";
   $classId = $row['class_id'];
   echo "<table cellpadding='10'><tr><td><img src=\"default.jpg\" height=\"70px\"></td><td>";
   echo "<h3 style=\"margin:0;\">" . $row['student_name'] . "</h3>";
   echo "<div style=\"font-size:70%; \">";
   echo "is from class <b><a href=\"./?class=" . $row['class_id'] . "\">" . getClassName($row['class_id']) . "</a></b>.<br />";
   echo $genderPrefix . " Class Teacher is <b>" . getClassTeacherLink($row['class_id']) . "</b> and " . $genderPrefix . " personal mentor is <b>" . getMentorName($row['student_id']) . "</b>.<br />";
   echo "Admission Number: <b>" . $row['adm_no'] . "</b><br />";
   echo "<b>" . gethouseName($row['house_id']) . "</b> House<br />Team <b>" . getTeamName($row['team_id']) . "</b><br />";
   echo "</div></td></tr></table>";
   
   $subjArr = Array();
   $res2 = mysql_query("SELECT `coursecode`.`course_code`,`coursecode`.`course_name` FROM `subjects`,`coursecode` WHERE `subjects`.`class_id` = '" . $row['class_id'] . "' AND `subjects`.`course_id` = `coursecode`.`course_code`");
   while($row2 = mysql_fetch_assoc($res2))
   {
       $subjArr[$row2['course_code']] = $row2['course_name'];
   }
   
   $query = "SELECT `marks`.`student_id`,`marks`.`exam_id`,`exams`.`exam_name`,`marks`.`course_code`,`marks`.`marks` FROM `marks`,`exams` WHERE `marks`.`exam_id` = `exams`.`exam_id` AND `marks`.`student_id` = '" . $row['student_id'] . "' ORDER BY `marks`.`exam_id` ASC";
   $res = mysql_query($query);
   if(mysql_num_rows($res) == 0) die("The student has not written any exam yet!");

   echo "<br /><table style='text-align:center' border='1' cellpadding='3' cellspacing='0'>\n<tr><th>Examination</th><th>Marks</th>";
   foreach($subjArr as $key=>$val) echo "<th>" . $val . "</th>";
   echo "<th>Total</th><th>Percentage</th><th>Rank</th></tr>\n";

   $marksArr = Array();
   $marksArr["exam_id"] = -1;
   while($row = mysql_fetch_assoc($res)) {
       if($marksArr["exam_id"] != $row['exam_id']) { //new row
           if($marksArr["exam_id"] != -1) {
	      $count = 0;
	      $sum   = 0;
	      echo "<tr><td rowspan=\"2\">" . $marksArr['exam_name'] . "</td>";
	      echo "<td>Student</td>";
    	      foreach($subjArr as $key=>$val) {error_log("fndabjb");
                  if(!isset($marksArr[$key])) {
	    	      $mark = "-";
	    	      continue;
		  }
	    	  else {
	              $mark = $marksArr[$key];
	    	      if($mark == -1) {
	                  $mark = "ab";
	    	      }
	    	      else {
                      	  $count = $count + 1;
        	      	  $sum = $sum + $marksArr[$key];
	    	      }
	      	  }
	      	  echo "<td>" . $mark . "</td>";
              }
    	      $avg = $count ? $sum/$count : 0;
    	      $avg = substr($avg,0,strpos($avg,".") + 3);
    	      echo "<td rowspan=\"2\">{$sum}</td><td rowspan=\"2\">{$avg}</td><td rowspan=\"2\">ranks #1</td></tr>";

    	      echo "<tr><td>Class Avg</td>";
    	      foreach($subjArr as $key=>$val) {
                  if(!isset($marksArr[$key])) {
	              $mark = "-";
	      	      continue;
	      	  }
	       	  else {
	      	      $mark = $marksArr[$key];
	      	      if($mark == -1) {
	                  $mark = "ab";
	    	      }
	      	  }
	      	  //$row = mysql_fetch_assoc(mysql_query("SELECT `class_id` FROM `students` WHERE `student_id` = '" . $_GET['sid'] . "'"));
	      	  //$classId = $row['class_id'];
	      	  echo "<td>" . $classId . getClassAvg(getClass($classId),1,$key) . "</td>";
    	      }
    	      echo "</tr>";
	   }
	   $marksArr = Array();
	   $marksArr["exam_id"]   = $row['exam_id'];
	   $marksArr["exam_name"] = $row['exam_name'];
	   $marksArr[$row['course_code']]      = $row['marks'];
       }
       else {
	   $marksArr[$row['course_code']] = $row['marks'];
       }
   }
    $count = 0;
    $sum   = 0;
    echo "<tr><td rowspan=\"2\">" . $marksArr['exam_name'] . "</td>";
    echo "<td>Student</td>";
    foreach($subjArr as $key=>$val) {
        if(!isset($marksArr[$key])) {
	    $mark = "-";
	    continue;
	}
	else {
	    $mark = $marksArr[$key];
	    if($mark == -1) {
	        $mark = "ab";
	    }
	    else {
                $count = $count + 1;
        	$sum = $sum + $marksArr[$key];
	    }
	}
	echo "<td>" . $mark . "</td>";
    }
    $avg = $count ? $sum/$count : 0;
    $avg = substr($avg,0,strpos($avg,".") + 3);
    echo "<td rowspan=\"2\">{$sum}</td><td rowspan=\"2\">{$avg}</td><td rowspan=\"2\">ranks #1</td></tr>";

    echo "<tr><td>Class Avg</td>";
    foreach($subjArr as $key=>$val) {
        if(!isset($marksArr[$key])) {
	    $mark = "-";
	    continue;
	}
	else {
	    $mark = $marksArr[$key];
	    if($mark == -1) {
	        $mark = "ab";
	    }
	}
	echo "<td>" . getClassAvg(getClass($classId),1,$key) . "</td>";
    }
    echo "</tr>";
   
   echo "</table>";
}
else {
?>
    Search for a student <input type="text" id="searchbox" style="padding:5px; font-size:13pt; " size="50" />
    <div id="autosuggestStudents"></div>
    <script>
        var inter = 0;
	$(document).ready(function() {
	//console.log($("#searchbox"));
	    $("#searchbox").focus();
	    $("#searchbox").keyup(function() {
	        if(inter) clearTimeout(inter);
 	        inter = setTimeout(getAS,300)
	    });
	});
	function getAS() {
	    if($("#searchbox").val() == "") {
	        $("#autosuggestStudents").html("");
	        return;
	    }
	    $("#autosuggestStudents").html("Loading...");
	    $.getJSON("./autosuggest.php?searchq=" + encodeURI($("#searchbox").val()),function(data) {
	        var str = "<h3 style=\"margin:0; margin-top:20px; padding:0;\">Search results for <i>" + $("#searchbox").val() + "</i></h3>";
		if(data.length) {
		str += "<ul>";
	        for(var i=0;i<data.length; ++i) {
		    str += "<li><div><b><a href=\"./student.php?sid=" + data[i].sid + "\">" + data[i].name + "</a></b></div>";
		    str += "<div class=\"s\">from class " + data[i].class + ". Admission No.:<b>" + data[i].adm_no + "</b><br />";
		    str += "House:" + data[i].house + ", Team " + data[i].team + "</div></li>";
		}
		str +="</ul>";
		}
		else {
		    str += "<div style=\"margin:10px; \">No search results found!</div>";
		}
		$("#autosuggestStudents").html(str);
	    });
	}

    </script>
<?php
}

?>
</div>
</body>
</head>
