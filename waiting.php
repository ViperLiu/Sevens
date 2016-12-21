<?php
session_start();
if( !isset($_SESSION['game']))
    die("No session");
include("class.php");
$table = new Table(-1);
$table->loadStatus($_SESSION['game']);
$deck = new Deck(-1,array());
$deck = $deck->loadStatus($_SESSION['game'],$table->turns);
$response = new Response();

if($table->counter == 0){
    $card = new Card(7,3);
    $response->setCard($card);
    if(!$deck->playCard($table,$card) || !$table->addCard($card)) $response->setMsg("Can't play this card!");
    else $response->setMsg("玩家".$table->turns." 出了一張牌!");
    $table->nextTurn();
    $table->saveStatus($_SESSION['game']);
    $deck->saveStatus($_SESSION['game']);
    echo json_encode($response);
    exit();
}

if($table->counter > 52){
    $score = array();
    for($i=0;$i<4;$i++){
        $deck = $deck->loadStatus($_SESSION['game'],$i);
        $coverInfo = new CoverInfo($deck);
        array_push($score,$coverInfo);
    }
    echo json_encode($score);
    exit();
}

$canPlay = false;
for($i=0;$i<$deck->cardNumber;$i++){
    $card = $deck->cards[$i];
    if($table->checkIsAvailable($card)){
        $canPlay = true;
        $response->setCard($card);
        if(!$deck->playCard($table,$card) || !$table->addCard($card)) $response->setMsg("Can't play this card!");
        else $response->setMsg("玩家".$table->turns." 出了一張牌!");
        $table->nextTurn();
        $table->saveStatus($_SESSION['game']);
        $deck->saveStatus($_SESSION['game']);
        echo json_encode($response);
        exit();
        break;
    }
}
if(!$canPlay){
    $selectedCard = rand(0,$deck->cardNumber-1);
    $card = $deck->cards[$selectedCard];
    $response->setCard($card);
    if(!$deck->coverCard($table,$card)) $response->setMsg("You can't cover card!");
    else $response->setMsg("玩家".$table->turns." 蓋了一張牌!");
    $table->nextTurn();
    $table->saveStatus($_SESSION['game']);
    $deck->saveStatus($_SESSION['game']);
    echo json_encode($response);
}
?>