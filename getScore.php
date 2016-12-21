<?php
session_start();
if( !isset($_SESSION['game']))
    die("No session");
include("class.php");
$table = new Table(-1);
$table->loadStatus($_SESSION['game']);
$deck = new Deck(-1,array());
//$deck = $deck->loadStatus($table->turns);
$response = new Response();
if($table->counter > 51){
    $score = array();
    for($i=0;$i<4;$i++){
        $deck = $deck->loadStatus($_SESSION['game'],$i);
        $coverInfo = new CoverInfo($deck);
        array_push($score,$coverInfo);
    }
    echo json_encode($score);
    exit();
}
?>