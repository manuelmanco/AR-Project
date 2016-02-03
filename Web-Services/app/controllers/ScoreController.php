<?php

/**
 * Class ScoreController
 *
 * Ce fichier étends la class AbstractPageSystem où sont accessibles:
 * Les super globales: $this->get, $this->get, $this->get, $this->get, $this->get
 *
 * @author : Kévin Vacherot & Kévin Siow
 *
 */

namespace App\Controllers;
use \App\Services;

class ScoreController extends \Core\System\AbstractPageSystem
{
    private $score;
    /**
    * __Constructeur: C'est dans le constructeur que l'on instancie le model utilisé par le module / controller
     * @return void
     */
    public function __construct()
    {
        $this->score = new \App\Models\Score();
    }


    /**
     * Méthode permettant de sauvegarder le highscore d'un joueur
     * @param   string  $player_highscore  Score réalisé
     * @param   string  $player_name  Nom du joueur ayant réalisé le score
     * @return array tableau contenant les clés d'erreurs, ou une clé de succes si absence d'erreur
     */
    public function saveScore()
    {
        $arrayErrors = array();

        if(isset($this->get['params']['player_highscore']) && !empty($this->get['params']['player_highscore']) &&
            isset($this->get['params']['player_name']) && !empty($this->get['params']['player_name'])
            ){
            $score = $this->get['params']['player_highscore'];
            $player_name = $this->get['params']['player_name'];
            if(!$this->score->insertScore($score, $player_name)){
                $arrayErrors['error']['save_score_failed'] = true;
            }
        } else {
            $arrayErrors['error']['missing_parameters'] = true;
        }

        return empty($arrayErrors) ? array('score_saved' => true) : $arrayErrors;
    }


    /**
     * Méthode permettant de retourner une liste de score par rapport à son placement dans tableau des scores dans un interval de temps défini
     * @param int $limit point de départ dans le tableau des scores. 0 par défaut
     * @param int $length longueur souhaité du tableau. 10 par défaut
     * @param string $period période choisi pour le tableau des scores (allTime -> Tous les temps [default] ; currentWeek -> Semaine en cours ; lastWeek -> Semaine dernière)
     * @return array tableau contenant la liste des scores
     */
    public function getHighScores()
    {
        $limit = isset($this->get['params']['limit']) ? $this->get['params']['limit'] : null;
        $offset = isset($this->get['params']['offset']) ? $this->get['params']['offset'] : null;
        $period = isset($this->get['params']['period']) ? $this->get['params']['period'] : null;

        $opts = array(
            'limit' => $limit,
            'offset' => $offset,
            'period' => $period,
        );

        return $this->score->selectHighScores($opts);
    }
}
