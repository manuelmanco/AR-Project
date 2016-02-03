<?php

/**
 * Class Score
 *
 * Cette classe, comme toutes les class contenues dans le dossier Models, hérite de la Class Database\Models
 * Cette classe hérite ainsi de l'objet PDO, mais également des requêtes pré-faites de la classe Models
 * TODO :
 *      - Paramètre supplémentaire pour la semaine en cours
 *      - Paramètre supplémentaire pour la semaine dernière
 *      - Par défaut on prends pour tout les temps
 *
 * @author : Kévin Vacherot & Kévin Siow
 *
 */

namespace App\Models;
use \PDO;

class Score extends \Core\Database\Models
{
    /**
     * __Constructeur: Appel automatiquement le constructeur de Models afin d'établir la connexion
     * avec la bdd et de récupérer l'objet PDO
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Méthode permettant de sauvegarder le highscore d'un joueur en bdd
     * @param   string  $player_highscore  Score réalisé
     * @param   string  $player_name  Nom du joueur ayant réalisé le score
     * @return bool true si insert, false sinon
     */
    public function insertScore($score, $player_name)
    {
        $query = $this->buildInsertQuery(
            'ar_scores',
            array(
                'player_name' => ':player_name',
                'player_highscore' => ':player_highscore',
                'score_date' => 'NOW()',
            )
        );

        return $this->executeWithBindedValues(
            $query,
            array(
                ':player_name' => array($player_name, PDO::PARAM_STR),
                ':player_highscore' => array($score, PDO::PARAM_STR),
            )
        );
    }


    /**
     * Méthode permettant de retourner une liste de score par rapport à son placement dans tableau des scores
     * @param array $opts contient les filtres à appliquer à la requête
     * @return array tableau avec la liste des scores choisis
     *
     */
    public function selectHighScores($opts)
    {
        $query = "SELECT
                            players.player_name
                            , players.player_highscore

                          FROM ar_scores AS players

                          WHERE TRUE";

        if($opts['period'] === "currentWeek"){
            $query .= ' AND YEARWEEK(score_date, 5) = YEARWEEK(CURDATE(), 5)';
        }

        $query .= " ORDER BY players.player_highscore DESC";

        if(isset($opts['limit']) && isset($opts['offset'])){
            $query .= " LIMIT :limit, :offset";
        }

        return $this->executeWithBindedValues(
            $query,
            array(
                ':limit' => array($opts['limit'], PDO::PARAM_INT),
                ':offset' => array($opts['offset'], PDO::PARAM_INT),
            ),
            'select',
            'all'
        );
    }
}
