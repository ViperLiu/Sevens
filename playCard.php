<?php
session_start();
if( !isset($_SESSION['game']))
    die("No session");
include("class.php");
if( !isset($_POST['cardColor']) || !isset($_POST['cardValue']))
    die("Missing parameter");

$color = $_POST['cardColor'];
$value = $_POST['cardValue'];
$card = new Card($value,$color);
$card7 = new Card(7,3);
$table = new Table(-1);
$deck = new Deck(-1,array());
$table->loadStatus($_SESSION['game']);
$deck = $deck->loadStatus($_SESSION['game'],0);
$response = new Response();
$response->setCard($card);
if(!$deck->playCard($table,$card) || !$table->addCard($card)){
    $response->setMsg("這張牌不能出!");
}
else{
    $response->setMsg("你出了一張牌!");
    $table->nextTurn();
}

$table->saveStatus($_SESSION['game']);
$deck = $deck->saveStatus($_SESSION['game']);
echo json_encode($response);
?>