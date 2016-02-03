<?php

/**
 * Class Developper
 *
 * Cette classe, comme toutes les class contenues dans le dossier Models, hérite de la Class Database\Models
 * Cette classe hérite ainsi de l'objet PDO, mais également des requêtes pré-faites de la classe Models
 *
 * @author : Kévin Vacherot & Kévin Siow
 *
 */

namespace App\Models;
use \PDO;

class Developper extends \Core\Database\Models
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
     * Méthode permettant de vérifier l'existence de l'utilisateur en bdd, en utilisant 3 types de filtres:
     * par Token, fb_id, mail / password, mail uniquement -- Tout en checkant le niveau de droit
     * @param   array  $options  array contenant les filtres à utiliser pour la requête
     * @return bool / string true s'il existe un user enregistré, false si pas de user enregistré, "multiple" si plusieurs users enregistrés
     */
    public function apiDevelopperExist($options)
    {
        $query = "SELECT developpers.dev_id FROM bh_webservice_dev AS developpers WHERE TRUE";

        if(isset($options['developper_name']) && isset($options['developper_secret'])){

            $query .= " AND developpers.dev_name = :developper_name AND developpers.dev_secret = :developper_secret";
            $values = array(
                ':developper_name' => array($options['developper_name'], PDO::PARAM_STR),
                ':developper_secret' => array($options['developper_secret'], PDO::PARAM_STR),
            );

        } elseif(isset($options['developper_token'])){

            $query .= " AND developpers.dev_token = :developper_token";
            $values = array(':developper_token' => array($options['developper_token'], PDO::PARAM_STR));

        }

        return $this->existingUniqResult($query, $values);
    }



    /**
     * Méthode permettant de renouveller le token du developpeur
     * @param   string  $developper_name  nom du developpeur
     * @param   string  $developper_secret  secret du developpeur
     * @param   string  $developper_token  nouveau token du developpeur
     * @return bool true si l'activation s'est effectué, false sinon
     */
    public function generateNewDevelopperToken($developper_name, $developper_secret, $developper_token)
    {
        $query = $this->buildUpdateQuery(
            'bh_webservice_dev',
            array(
                'dev_token' => ':developper_token',
                'dev_token_generation_date' => 'NOW()',
            ),
            array(
                'dev_name' => ':developper_name',
                'dev_secret' => ':developper_secret',
            )
        );

        return $this->executeWithBindedValues(
            $query,
            array(
                ':developper_token' => array($developper_token, PDO::PARAM_STR),
                ':developper_name' => array($developper_name, PDO::PARAM_STR),
                ':developper_secret' => array($developper_secret, PDO::PARAM_STR),
            )
        );
    }
}
