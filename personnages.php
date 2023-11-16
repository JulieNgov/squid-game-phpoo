<?php

//Abstract avant class car on utilise des fonctions abstracts dans la classe
abstract class Character {

    //Attributs qui représenteront nos personnages
    //protected pour qu'il ne soit pas accessible dans les classes en dehors de la classe Character,Hero et Enemy (héritage)
    protected $nom;
    protected $nbrbilles;
    protected $bonus;
    protected $malus;

    //On initialise les propriétés du personnage
    public function __construct($nom, $nbrbilles, $bonus, $malus) {
        $this->nom = $nom;
        $this->nbrbilles = $nbrbilles;
        $this->bonus = $bonus;
        $this->malus = $malus;
    }

    //getter pour accéder aux données à l'extérieur de la classe, car nos attributs sont en private
    
    public function getNom() {
        return $this->nom;
    }

    public function getNbrBilles() {
        return $this->nbrbilles;
    }

    //Fonctions similaires pour Hero et Enemy, donc on les met en abstract
    //public car il sera utilisé en dehors de la classe
    //note:EnnemieBilles se trouvent dans la classe Game
    public abstract function LeRound($EnnemieBilles);

    //protected car il sera utilisé uniquement dans Hero et Enemy
    protected abstract function PairOuImpair();
}

//Extends pour dire qu'il va hériter de la classe Character
class Hero extends Character {
    public function LeRound($EnnemieBilles) {
        //Dans une variable, on met le résultat de la fonction PairouImpair
        //pour pouvoir l'utiliser dans le if plus tard
        //Donc ResultatRound sera égal au chiffre 0 ou 1
        $ResultatRound = $this->PairOuImpair();

        //Si ResultatRound = 0, alors le nombre de billes de l'ennemi%2 = 0 donc c'est pair, et si c'est égal à 1, nbr de billes%2 = 1 donc impair
        //On va retourner true si on gagne, et false si on perd.
        if ($ResultatRound === $EnnemieBilles % 2) {
            //On ajoute les billes gagnés au personnage (les billes de l'ennemi + le bonus)
            $this->nbrbilles += ($this->bonus +  $EnnemieBilles);
            echo "Gagné, tu as reçu " . ($this->bonus +  $EnnemieBilles) . " billes." . "<br>" . "<br>";
            return true;
        } else {
            //On enlève les billes perdues au personnage
            //Les billes de l'ennemi + le malus
            $this->nbrbilles -= ($this->malus +  $EnnemieBilles);
            echo "Perdu, tu as perdu " . ($this->malus +  $EnnemieBilles) . " billes." . "<br>" . "<br>";
            return false;
        }
    }

    //BONUS: Fonction triche en public car utilisée en dehors de la classe
    //Fonction utilisée si le héro a triché
    public function Triche($EnnemieBilles) {
        //On ajoute uniquement les billes de l'ennemi, pas de bonus
        $this->nbrbilles += ($EnnemieBilles);
        echo "Tu as triché, tu as reçu " . ($EnnemieBilles) . " billes." . "<br>" . "<br>";
        //true car il a gagné par défaut
        return true;
    }

    //Va renvoyer un chiffre entre 0 et 1
    //Utils:: (car la fonction est static)
    protected function PairOuImpair() {
        return Utils::generateRandomNumber(0, 1);
    }
}

//Extends pour dire qu'il va hériter de la classe Character
class Enemy extends Character {
    //Demande un age pour le bonus
    private $age;

    //Constructor pour initialiser les propriétés de l'ennemi
    public function __construct($nom, $nbrbilles, $age) {
        //On appelle le constructor de Character (son parent) car différent de Hero (surcharge)
        //L'ennemi n'a pas de bonus ou malus, donc on les met tous les 2 à zero
        parent::__construct($nom, $nbrbilles, 0, 0);
        $this->age = $age;
    }

    //getter
    public function getAge() {
        return $this->age;
    }

    public function LeRound($EnnemieBilles) {
        //??
    }

    //Va renvoyer un chiffre entre 0 et 1
    protected function PairOuImpair() {
        return Utils::generateRandomNumber(0, 1);
    }
}