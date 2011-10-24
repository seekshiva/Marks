<?php
require_once("config.inc.php");

$MYSQL_CON = mysql_connect($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS);
mysql_select_db($MYSQL_DB );


?>