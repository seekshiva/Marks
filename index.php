<?php include("functions.lib.php"); ?>
<!doctype html>
<html>
<head>
<title>JKDSHKJ</title>
</head>
<body>
<a href="javascript:history.back(1)">Back</a> <a href="./">Home</a><br />
<?php 
if(isset($_GET['class'])) {
getStudentsFromClass($_GET['class']);
}
else if(isset($_GET['team'])) {
getStudentsFromTeam($_GET['team']);
}
else if(isset($_GET['house'])) {
getStudentsFromTeam($_GET['team']);
}
else {
    generateClassesList();
    //generateTeamsList();
    generateHousesList();
}
?>
</body>
</html>