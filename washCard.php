<?php
/*

color 0~3 分別為黑桃、紅心、方塊、梅花

*/
session_start();
include("class.php");

if(!isset($_SESSION['game']))
    $_SESSION['game'] = microtime(false);

//初始化牌組
$count = 0;
for($i=0;$i<=3;$i++){
    for($j=1;$j<=13;$j++){
        $deck[$count] = new Card($j,$i);
        $count++;
    }
}

//洗牌
for($j=1;$j<=2;$j++){
    for($i=51;$i>=0;$i--){
        $x = rand(0,$i);
        $temp = $deck[$x];
        $deck[$x] = $deck[$i];
        $deck[$i] = $temp;
    }
}

//找出梅花7
$temp = new Card(7,3);
$pos = 0;
for($i=0;$i<52;$i++){
    if($deck[$i] == $temp){
        $pos = $i;
        break;
    }
}
$pos = intval($pos/13);
//發牌(發四副)
for($i=0,$index=0;$i<4;$i++,$index+=13){
    $tempDeck = array_slice($deck,$index,13);
    $handDeck[$i] = new Deck($i,$tempDeck);
    $handDeck[$i]->saveStatus($_SESSION['game']);
}

$table = new Table($pos);
$table->saveStatus($_SESSION['game']);
echo json_encode($handDeck[0]);
?>