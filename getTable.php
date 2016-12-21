<?php
session_start();
if( !isset($_SESSION['game']))
    die("No session");
include("class.php");
$table = new Table(-1);
$table->loadStatus($_SESSION['game']);
echo json_encode($table);
?>