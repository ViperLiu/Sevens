<?php
class Card{
    var $value;
    var $color;
    function Card($value,$color){
        $this->value = $value;
        $this->color = $color;
    }
}

class Response{
    var $msg;
    var $card;
    function setMsg($msg){
        $this->msg = $msg;
    }
    function setCard($card){
        $this->card = $card;
    }
}

class CoverInfo{
    var $infoOwner;
    var $coveredCards;
    var $coveredCardsNumber;
    var $coveredValue;
    function CoverInfo($deck){
        $this->infoOwner = $deck->owner;
        $this->coveredCards = $deck->coveredCards;
        $this->coveredCardsNumber = $deck->coveredCardsNumber;
        $this->coveredValue = $deck->coveredValue;
    }
}

class Deck{
    var $owner;
    var $cardNumber;
    var $cards;
    var $coveredCards;
    var $coveredCardsNumber;
    var $coveredValue;
    function Deck($owner,$cards){
        $this->owner = $owner;
        $this->cards = $cards;
        $this->cardNumber = sizeof($cards);
        $this->coveredCards = Array();
        $this->coveredCardsNumber = 0;
        $this->coverValue = 0;
    }
    function hasThisCard($card){
        for($i=0;$i<$this->cardNumber;$i++){
            if($this->cards[$i] == $card)
                return $i;
        }
        return -1;
    }
    function hasCardsToPlay($table,$card){
        for($i=0;$i<$this->cardNumber;$i++){
            if($table->checkIsAvailable($this->cards[$i]))
                return true;
        }
        return false;
    }
    function playCard($table,$card){
        $key = $this->hasThisCard($card);
        if(!$table->checkIsAvailable($card) || $key == -1)
            return false;
        unset($this->cards[$key]);
        $this->cards = array_values($this->cards);
        $this->cardNumber = sizeof($this->cards);
        return true;
    }
    function coverCard($table,$card){
        $key = $this->hasThisCard($card);
        if($this->hasCardsToPlay($table,$card) || $key==-1)//有牌可以出，是在蓋三小啦
            return false;
        array_push($this->coveredCards,$this->cards[$key]);
        $this->coveredValue += $card->value;
        $this->coveredCardsNumber = sizeof($this->coveredCards);
        
        unset($this->cards[$key]);
        $this->cards = array_values($this->cards);
        $this->cardNumber = sizeof($this->cards);
        return true;
    }
    function saveStatus($session){
        $filename = "status/".$session."/player".$this->owner.".sts";
        $dirname = dirname($filename);
        if (!is_dir($dirname)){
            mkdir($dirname, 0755, true);
        }
        $myfile = fopen("status/".$session."/player".$this->owner.".sts", "w");
        $text = serialize($this);
        fwrite($myfile, $text);
        fclose($myfile);
    }
    function loadStatus($session,$owner){
        $filename = "status/".$session."/player".$this->owner.".sts";
        $dirname = dirname($filename);
        if (!is_dir($dirname)){
            mkdir($dirname, 0755, true);
        }
        $myfile = fopen("status/".$session."/player".$owner.".sts", "r");
        $text = unserialize(fread($myfile,filesize("status/".$session."/player".$owner.".sts")));
        fclose($myfile);
        return $text;
    }
}

class Table{
    var $sevenUp;
    var $sevenDown;
    var $turns;
    var $counter;
    function Table($turns){
        $this->sevenUp = Array(7,7,7,7);
        $this->sevenDown = Array(7,7,7,7);
        $this->turns = $turns;
        $this->counter = 0;
    }
    function checkIsAvailable($card){
        $color = $card->color;
        $value = $card->value;
        $upAvailable = $this->sevenUp[$color];
        $downAvailable = $this->sevenDown[$color];
        if($this->counter == 0 && $color != 3)
            return false;
        else if($value == $upAvailable || $value == $downAvailable)
            return true;
        else
            return false;
    }
    function addCard($card){
        if(!$this->checkIsAvailable($card))
            return false;
        $color = $card->color;
        $value = $card->value;
        if($value == 7){
            $this->sevenUp[$color]++;
            $this->sevenDown[$color]--;
        }
        else if($value > 7){
            $this->sevenUp[$color]++;
        }
        else if($value < 7){
            $this->sevenDown[$color]--;
        }
        return true;
    }
    function nextTurn(){
        $this->counter = $this->counter+1;
        $this->turns = ($this->turns+1)%4;
    }
    function saveStatus($session){
        $filename = "status/".$session."/table.sts";
        $dirname = dirname($filename);
        if (!is_dir($dirname)){
            mkdir($dirname, 0755, true);
        }
        $myfile = fopen("status/".$session."/table.sts", "w");
        $text = json_encode($this);
        fwrite($myfile, $text);
        fclose($myfile);
    }
    function loadStatus($session){
        $filename = "status/".$session."/table.sts";
        $dirname = dirname($filename);
        if (!is_dir($dirname)){
            mkdir($dirname, 0755, true);
        }
        $myfile = fopen("status/".$session."/table.sts", "r");
        $text = json_decode(fread($myfile,filesize("status/".$session."/table.sts")),true);
        $this->sevenUp = $text["sevenUp"];
        $this->sevenDown = $text["sevenDown"];
        $this->turns = $text['turns'];
        $this->counter = $text['counter'];
        fclose($myfile);
    }
}
?>