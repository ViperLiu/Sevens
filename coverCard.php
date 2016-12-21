<?php
session_start();
include("class.php");
if( !isset($_SESSION['game']))
    die("No session");
if( !isset($_POST['cardColor']) || !isset($_POST['cardValue']))
    die("Missing parameter");

$color = $_POST['cardColor'];
$value = $_POST['cardValue'];
$card = new Card($value,$color);
$table = new Table(-1);
$table->loadStatus($_SESSION['game']);
$deck = new Deck(-1,array());
$deck = $deck->loadStatus($_SESSION['game'],0);
$response = new Response();
$response->setCard($card);
if(!$deck->coverCard($table,$card)) $response->setMsg("這張牌不能蓋!");
else{
    $response->setMsg("你蓋了一張牌!");
    $table->nextTurn();
}
$table->saveStatus($_SESSION['game']);
$deck->saveStatus($_SESSION['game']);
echo json_encode($response);
?>