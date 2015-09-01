<?php

require_once('../config.php');

	$oldname = $_POST['oldname'];
	$newname = $_POST['newname'];

$con = mysql_connect($CFG->dbhost,$CFG->dbuser,$CFG->dbpass);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db($CFG->dbname, $con);

$result = mysql_query("update mdl_user set username = '$newname' where username = '$oldname'");

echo("Number of users with userid $oldname: ");

$result = mysql_query("SELECT count(*) as count FROM mdl_user where username='$oldname'");

while($row = mysql_fetch_array($result))
  {
  echo $row['count'];
  }

echo("<p>Number of users with userid $newname: ");

$result = mysql_query("SELECT count(*) as count FROM mdl_user where username='$newname'");

while($row = mysql_fetch_array($result))
  {
  echo $row['count'];
  }

mysql_close($con);

?>
