<?php

/**
 * Class Database
 *
 * Cette classe est un singleton chargé d'établir la connexion avec la base de données et de générér l'objet PDO
 *
 * @author : Kévin Vacherot & Kévin Siow
 *
 */

namespace Core\Database;

use \PDO;
use \PDOException;

class Database
{
    /**
    * @var object  Instance unique de la Class Database (Singleton)
    */
    private static $db_instance;

    /**
    * @var string  Nom de la base de données utilisé pour l'application
    */
    private $db_name;

    /**
    * @var string  Nom d'hôte
    */
    private $db_host;

    /**
    * @var string  Nom d'utilisateur
    */
    private $db_user;

    /**
    * @var string  Mot de passe de l'utilisateur
    */
    private $db_pass;

    /**
    * @var object  Instance de PDO
    */
    private $pdo;


    /**
     * __Constructeur:
     * L'opérateur de portée appliqué au constructeur est "private"
     * Cela permet de s'assurer que la class ne puisse être instanciée que via la méthode runDb()
     * Cette class a en effet la particularité d'être un singleton
     *
     * Le constructeur initialise les propriétés de la classe
     * @param string    $db_name  Nom de la base de données utilisé pour l'application
     * @param string    $db_host  Nom d'hôte
     * @param string    $db_user  Nom d'utilisateur
     * @param string    $db_pass  Mot de passe de l'utilisateur
     * @return void
     */
    private function __construct($db_name, $db_host = 'localhost', $db_user = 'root', $db_pass = 'root')
    {
        $this->db_name = $db_name;
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->getPdo();
    }


    /**
     * Méthode appelée par getPdoObject() depuis la classe Models afin de récupérer l'objet pdo
     * La classe Database applique le principe de fonctionnement du singleton
     * la méthode runDb() vérifie alors si la classe est déjà instanciée, et ne créer une nouvelle instance que dans le cas contraire
     * @param string    $db_name  Nom de la base de données utilisé pour l'application
     * @param string    $db_host  Nom d'hôte
     * @param string    $db_user  Nom d'utilisateur
     * @param string    $db_pass  Mot de passe de l'utilisateur
     * @return object Retourne l'instance de la classe.
     */
    public static function runDb($db_name, $db_host, $db_user, $db_pass)
    {
        if(!self::$db_instance instanceof self){
            self::$db_instance = new self($db_name, $db_host, $db_user, $db_pass);
        }

        return self::$db_instance;
    }


    /**
     * Méthode appelée par le constructeur et par getPdoObject depuis la class system
     * Cette méthode initialise la propriété $pdo avec une instance de pdo, et ne le fait que si cette dernière est null
     * @return object Retourne l'instance PDO.
     */
    public function getPdo()
    {
        if($this->pdo === null){
            try {
                $option = array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                );
                $pdo = new PDO('mysql:host=' . $this->db_host . '; dbname=' . $this->db_name . '', $this->db_user, $this->db_pass, $option);
                $this->pdo = $pdo;
            } catch(PDOException $e) {
                if(defined('DEBUG') && DEBUG){
                    die($e->getMessage());
                } else {
                    return false;
                }
            }
        }

        return $this->pdo;
    }
}
