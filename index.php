<?php

require_once('personnages.php');
require_once('game.php');

//On crée une fonction Utils pour pouvoir obtenir un nombre random
//on utilise abstract et static afin de pouvoir utiliser la fonction dans d'autres classes
abstract class Utils {
    public static function generateRandomNumber($min, $max) {
        return rand($min,$max);
    }
}

$game = new Game();
$game->startGame();

?>