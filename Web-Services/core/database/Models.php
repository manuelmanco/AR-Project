<?php

/**
 * Class Models
 *
 * Elle génére la connexion avec la base de données ainsi que la récupération de l'objet PDO
 * Cette classe sera étendu par toutes les classes appartenants au dossier Models afin d'hériter de l'objet PDO
 * Ainsi que des requêtes préfaites
 *
 * @author : Kévin Vacherot & Kévin Siow
 *
 */

namespace Core\Database;
use \PDO;
use \PDOException;

class Models
{
    /**
    * @var object  Objet PDO
    */
    protected $pdoObject;

    /**
    * @var array  Tableau contenant les information nécessaires à établir la connexion avec la base de données
    */
    private $dbConfiguration;


    /**
     * __Constructeur: Initialise les propriétés de la classe, à savoir la configuration pour la bdd et l'objet PDO
     * @return void
     */
    public function __construct()
    {
        if(defined('ENV')){
            switch (ENV) {
                case 'DEV':
                    $this->dbConfiguration = $this->getDbConfiguration()['dev'];
                    break;

                case 'TEST':
                    $this->dbConfiguration = $this->getDbConfiguration()['test'];
                    break;

                case 'PROD':
                    $this->dbConfiguration = $this->getDbConfiguration()['prod'];
                    break;
            }
        }

        $this->pdoObject = $this->getPdoObject();
    }


    /**
     * Méthode appelée par le constructeur
     * Elle sert à Initialiser la propriété $pdoObject qui devient une instance de PDO
     * @return object Retourne l'instance de PDO.
     */
    private function getPdoObject()
    {
        return Database::runDb(
            $this->dbConfiguration['db_name']
            , $this->dbConfiguration['db_host']
            , $this->dbConfiguration['db_user']
            , $this->dbConfiguration['db_pass']
        )->getPdo();
    }


    /**
     * Méthode appelée par le constructeur
     * Elle est utilisée afin de parcourir le fichier YAML config_db.yaml afin d'en extraire un tableau
     * Ce tableau contient les information nécessaires à établir la connexion avec la base de données
     * Ce tableau est ensuite utilisé pour initialiser la propriété $dbConfiguration
     * @return array   Tableau contenant les information nécessaire à la connexion à la base de données
     */
    private function getDbConfiguration()
    {
        return \Core\Configuration::parseYamlFile('database/config_db');
    }


    /**
     * Méthode appelée dans le catch du try and catch de chaque requête
     * Elle est utilisée pour gérer le message d'erreur en prenant en compte l'environnement de dev en cours
     * @param   object  Instance de ce type Exception servant à tracer le message d'erreur
     * @return void / false si en environnement de prod
     */
    protected function returnError($e)
    {
        if(defined('DEBUG') && DEBUG){
            die($e->getMessage());
        }

        return false;
    }


    /**
     * Méthode destinée à être appelé par les classes enfants
     * Cette méthode sert à construire une requête de type INSERT en fonction des argument qui lui sont envoyés
     * @param string  $table  La table sur laquelle effectuer l' INSERT
     * @param array  $column_values  Tableau associatif contenant en "clé" le nom des colonnes, et en 'valeurs" les values à y insérer
     * @return string   l'assemblage des morceaux de la requête de type INSERT
     */
    protected function buildInsertQuery($table, $column_values)
    {
        $query = "INSERT INTO ";
        $query .= $table . " (";

        $i = 0;
        foreach($column_values as $column => $value){
            $i++;
            $query .= $i === 1 ? $column : ', ' . $column;
        }

        $query .= ") VALUES (";

        $j = 0;
        foreach($column_values as $column => $value){
            $j++;
            $query .= $j === 1 ? $value : ', ' . $value;
        }

        $query .= ")";

        return $query;
    }


    /**
     * Méthode destinée à être appelé par les classes enfants
     * Cette méthode sert à construire une requête de type UPDATE en fonction des argument qui lui sont envoyés
     * @param string  $table  La table sur laquelle effectuer l'UPDATE
     * @param array  $column_values    Tableau associatif contenant en "clé" le nom des colonnes, et en 'valeurs" les nouvelles values
     * @param array  $where_clauses     Tableau associatif contenant les clauses wheres avec en clé la colonne sur laquelle filtrer
     * et en "valeur" la valeur à filtrer
     * @return string   l'assemblage des morceaux de la requête de type UPDATE
     */
    protected function buildUpdateQuery($table, $column_values, $where_clauses)
    {
        $query = "UPDATE ";
        $query .= $table . " SET ";

        $i=0;
        foreach($column_values as $colums => $new_value){
            $i++;
            $query .= $i === 1 ? $colums . ' = ' . $new_value : ', ' . $colums . ' = ' . $new_value;
        }

        $query .= " WHERE TRUE ";

        foreach($where_clauses as $colums => $value){
            $query .= ' AND ' . $colums . ' = ' . $value;
        }

        return $query;
    }


    /**
     * Méthode destinée à être appelé par les classes enfants
     * Cette méthode sert à construire une requête de type DELETE en fonction des argument qui lui sont envoyés
     * @param string  $table  La table sur laquelle effectuer le DELETE
     * @param array  $where_clauses  Tableau associatif contenant les clauses wheres avec en clé la colonne sur laquelle filtrer
     * et en "valeur" la valeure à filtrer
     * @return string   l'assemblage des morceaux de la requête de type DELETE
     */
    protected function buildDeleteQuery($table, $where_clauses)
    {
        $query = "DELETE FROM " . $table;
        $query .= " WHERE TRUE ";

        foreach($where_clauses as $colums => $value){
            $query .= ' AND ' . $colums . ' = ' . $value;
        }

        return $query;
    }


    /**
     * Méthode destinée à être appelé par les classes enfants
     * Cette méthode sert à exécuter une requête de type SELECT COUNT afin de compter le nombre de résultat
     * Le try and catch est également effectué à ce niveau
     * @param string  $table  La table sur laquelle effectuer le SELECT COUNT
     * @param string  $colonne  La colonne sur laquelle effectuer le SELECT COUNT
     * @return int   Le nombre de resultats généré par la requête
     */
    protected function countResults($table, $colonne)
    {
        try {
            $q = $this->pdoObject->query("SELECT COUNT(" . $colonne . ") FROM " . $table);
            $q->setFetchMode(PDO::FETCH_NUM);
            $nb_results = $q->fetch();
            $q->closeCursor();

            return $nb_results;
        } catch (PDOException $e) {
            return $this->returnError($e);
        }
    }


    /**
     * Méthode destinée à être appelé par les classes enfants
     * Cette méthode sert à exécuter une requête de type SELECT et à en retourner le tableau de resultats
     * Le try and catch est également effectué à ce niveau
     * @param string  $query  La requête SQL de type SELECT
     * @param string  $extend  Indique si on fetch une seule ligne de resultat ou plusieurs (one/all)
     * @return array   Retour de la requête
     */
    protected function fetchResults($query, $extend = 'all')
    {
        try{
            $q = $this->pdoObject->query($query);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            $results = $extend !== 'one'  ? $q->fetchAll() : $q->fetch();
            $q->closeCursor();

            return $results;
        } catch (PDOException $e) {
            return $this->returnError($e);
        }
    }


    /**
     * Méthode destinée à être appelé par les classes enfants
     * Cette méthode sert à exécuter des requêtes de type SELECT, INSERT, UPDATE et DELETE utilisants des paramêtres nommés
     * Et nécessitant donc des bindValue (permettant d'attribuer la valeur à chaque paramètre tout en typant l'entrée)
     * @param string  $query  La requête SQL de type SELECT, INSERT, UPDATE et DELETE
     * @param array  $arrayTypedValues  Tableau contenant les paramètres nommée, leurs valeurs et typages respectifs
     * @param string  $type  Indique le type de la requête
     * @param string  $extend   Indique si on fetch une seule ligne de resultat ou plusieurs (one/all)
     * @return array/bool   Tableau de resultats si $typeSelect = true, true si execution d'un autre type de requête SQL
     */
    protected function executeWithBindedValues($query, $arrayTypedValues = false, $type='default', $extend = 'all')
    {
        try{
            $q = $this->pdoObject->prepare($query);

            if($arrayTypedValues !== false) {
                foreach($arrayTypedValues as $key => $value){
                    !is_null($value[1]) ? $q->bindValue($key, $value[0], $value[1]) : $q->bindValue($key, $value[0]) ;
                }
            }
            $q->execute();

            if($type === 'select'){
                $return = $extend === 'all' ? $q->fetchAll(PDO::FETCH_ASSOC) : $q->fetch(PDO::FETCH_ASSOC);
            } else {
                if($type === 'insert'){
                    $return = $this->pdoObject->lastInsertId();
                } else {
                    $return = true;
                }
            }
            $q->closeCursor();

            return $return;
        } catch (PDOException $e) {
            return $this->returnError($e);
        }
    }


    /**
     * Méthode destinée à être appelé par les classes enfants
     * Cette méthode sert à exécuter des requêtes de type SELECT
     * Et nécessitant eventuellement des bindValue (permettant d'attribuer la valeur à chaque paramètre tout en typant l'entrée)
     * Elle permet de déterminer si un resultat est présent en bdd, s'il ne l'est qu'une fois, ou en plusieur exemplaires
     * @param string  $query  La requête SQL de type SELECT
     * @param array  $arrayTypedValues  Tableau contenant les paramètres nommée, leurs valeurs et typages respectifs
     * @return string/bool   string 'multiple' si plusieurs résultats, false si aucun, true si un seul
     */
    protected function existingUniqResult($query, $arrayTypedValues = false)
    {
        try{
            $q = $this->pdoObject->prepare($query);

            if($arrayTypedValues){
                foreach($arrayTypedValues as $key => $value){
                    $q->bindValue($key, $value[0], $value[1]);
                }
            }
            $q->execute();

            if($q->rowCount() < 1){
                $check = false;
            } elseif ($q->rowCount() == 1) {
                $check = true;
            } else {
                $check = 'multiple';
            }

            $q->closeCursor();

            return $check;
        } catch (PDOException $e) {
            return $this->returnError($e);
        }
    }
}
