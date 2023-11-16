<?php

class Game {
    //Attributs qui seront les héros et les ennemis (seront des tableaux)
    private $heroes;
    private $enemies;

    // On crée les instances des héros grâce au constructor que l'on a créé dans notre classe Character
    private function AllHeroes() {
        //on rappelle : $nom, $nbrbilles, $billesGagnés, $billesPerdues
        //On les rentre dans l'array heroes
        $this->heroes[] = new Hero('Seong Gi-hun', 15, 2, 1);
        $this->heroes[] = new Hero('Kang Sae-byeok', 25, 1, 2);
        $this->heroes[] = new Hero('Cho Sang-woo', 35, 0, 3);
    }

    // On crée les instances des ennemies à l'aide de la boucle for pour éviter d'en écrire 20 différents
    private function AllEnemies() {
        //On commence de 1 à 20 inclus, donc il y aura 20personnages en tout
        for ($i = 1; $i <= 20; $i++) {
            $nom = "'Ennemi $i'";
            //On randomize le nombre de billes et l'age des ennemies
            $nbrbilles = Utils::generateRandomNumber(1, 20);
            $age = Utils::generateRandomNumber(20, 90);

            //A chaque boucle, on crée un nouvel ennemi avec les variables écrits plus haut
            //On les rentre dans l'array enemies
            $this->enemies[] = new Enemy($nom, $nbrbilles, $age);
        }
    }

    // Constructor pour initialiser tous les héros et ennemies avant le début du jeu
    public function __construct() {
        $this->AllHeroes();
        $this->AllEnemies();
    }

    //On choisit un hero random entre 0 et 2
    //On commence par 0 car hero et enemies sont des arrays
    private function RandomHero() {
        return $this->heroes[Utils::generateRandomNumber(0, 2)];
    }

    //Au début du jeu, on choisit un ennemi random entre 0 et 19
    private function RandomEnemy() {
        //Count pour avoir le nombre total d'ennemis dans l'array enemies, puis -1 car on commence par 0
        //On utilise count pour avoir la length de l'array enemies, car plus tard on devra enlever les ennemis battus du tableau au lieu de mettre (0, 19)
        //puis -1 car un array commence par 0
        return $this->enemies[Utils::generateRandomNumber(0, count($this->enemies) - 1)];
    }

    // On retourne le nombre max de rounds, avec $difficulté qui sera choisi lorsque le jeu commence
    private function MaxRounds($difficulté) {
        switch ($difficulté) {
            case 1:
                return 5;
            case 2:
                return 10;
            case 3:
                return 20;
            default:
                return 5;
        }
    }

    //Le jeu commence
    public function startGame() {
        echo "Le jeu des billes commence!!!" . "<br>";

        //On choisit au hasard un héro et une difficulté grâce à la fonction RandomHero
        //On choisit au hasard la difficulté (entre 1 et 3)
        //On choisit au hasard si on est loyal ou pas (1 ou 2)
        $hero = $this->RandomHero();
        $difficulté = Utils::generateRandomNumber(1, 3);
        $loyal = Utils::generateRandomNumber(1, 2);

        echo $hero->getNom() . " joue avec " . $hero->getNbrBilles() . " billes.";
        echo "Niveau de difficulté : ";
        if($difficulté == 1) {
            echo "Facile" . "<br>";
        } else if($difficulté == 2) {
            echo "Difficile" . "<br>";
        } else {
            echo "Impossible" . "<br>";
        }

        //On incrémentera le nombre de round à chaque fin de la boucle while
        $round = 1;

        // Tant que le nbr de bille du héro n'est pas à 0 et que le nombre de round maximum n'est pas rempli,
        //Alors le round (while) continue
        while ($hero->getNbrBilles() > 0 && $round <= $this->MaxRounds($difficulté)) {
            echo "Round " . $round . "<br>";
            //Incrémente
            $round++;

            // On choisit un random ennemi à affronter
            $enemy = $this->RandomEnemy();
            $EnnemieBilles = $enemy->getNbrBilles();

            //Echo de ce que possède le héro et l'ennemi au départ
            echo $hero->getNom() . " possède " . $hero->getNbrBilles() . " billes." . "<br>";
            echo $enemy->getNom() . " possède " . $EnnemieBilles . " billes." . "<br>";

            //BONUS: On prend l'age de l'ennemi, s'il a plus de 70ans
            if($enemy->getAge() > 70) {
                //loyal (renvoie 1 ou 2), si 1, le héro est loyal et donc on sort du if et on continue la game
                if($loyal == 1) {
                    continue;
                } else {
                    //si loyal = 2, le héro triche et on appelle la fonction triche de la classe Hero
                    //puis break pour finir la boucle sans lire le reste du while
                    $result = $hero->Triche($EnnemieBilles);
                    break;
                }
            }
            //$Result aura comme résultat soit true, soit false car LeRound est un boolean
            //On demande au héro de jouer en appelant la fonction LeRound
            $result = $hero->LeRound($EnnemieBilles);

            //On update le round, donc si le $result est true,
            if ($result) {
                //On enlève l'ennemi de son array enemies
                //array search car on a que le nom de l'ennemi, donc on va chercher son nom dans l'array enemies pour avoir son emplacement dans l'array
                //array_splice pour enlever du tableau, et que les éléments soient remplacés (efficace pour avoir la nouvelle length à chaque round)
                array_splice($this->enemies, array_search($enemy, $this->enemies), 1);
            }
        }

        // Resultat final quand la boucle while est terminée
        //S'il reste des billes au héro, il gagne
        if ($hero->getNbrBilles() > 0) {
            echo "Bravo! " . $hero->getNom() . " a gagné 45.6 billion de Won!" . "<br>";
        } else {
            //Sinon il perd si le héro n'a plus de billes
            echo $hero->getNom() . " a perdu et est mort" . "<br>";
        }
    }
}

?>