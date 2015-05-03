<?php

include_once("db.php");

$id=$_GET['id'];

mysql_query("DELETE FROM news WHERE id='$id'");

mysql_close();

echo "Deleted!";

?>
