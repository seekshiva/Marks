<?php include("functions.lib.php"); ?>
<!doctype html>
<html>
<head>
<title>JKDSHKJ</title>
<link rel="stylesheet" href="main.css">
<script>
var classId = <?php echo isset($_GET['class'])?$_GET['class']:0; ?>, 
houses = [<?php echo getHousesList(); ?>],
teams = [<?php echo getTeamsList(); ?>];
</script>
<script src="script.js"></script>
</head>
<body>
<a href="javascript:history.back(1)">Back</a> <a href="./">Home</a><br />
<div id="wrapper">
<?php 
if(isset($_GET['addclass'])) {
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
else if(isset($_GET['class'])) {
    getStudentsFromClass();
}
else if(isset($_GET['team'])) {
    getStudentsFromTeam($_GET['team']);
}
else if(isset($_GET['house'])) {
    getStudentsFromHouse($_GET['house']);
}
else {
    generateClassesList();
    //generateTeamsList();
    generateHousesList();
}
?>
</div>
</body>
</html>