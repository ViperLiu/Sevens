<?php
session_start();
if( !isset($_SESSION['game']))
    die("No session");
include("class.php");
$deck = new Deck(-1,array());
$deck = $deck->loadStatus($_SESSION['game'],0);
echo json_encode($deck);
?>