<?php
session_start();
$_SESSION['user'] = "0";
$_SESSION['user_type'] = "0";

unset($_SESSION['user']);
unset($_SESSION['user_type']);

header("Location: ./student.php");
?>