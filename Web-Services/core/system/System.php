<?php

/**
 * Class System
 *
 * Cette classe est un singleton destiné à démarrer l'architecture
 *
 * @author : Kévin Vacherot & Kévin Siow
 *
 */

namespace Core\System;

class System
{
    /**
    * @var object  Instance unique de la Class System (Singleton)
    */
    private static $instance;

    /**
    * @var array  Tableau contenant la configuration du serveur à adopter (dev / test / prod)
    */
    private $environmentConfiguration;

    /**
    * @var object  Instance de la Class PageSystem
    */
    private $pageSystem;

    /**
    * @var array  Tableau contenant les information nécessaire à la connexion à la base de données, et à la création de l'object PDO
    */
    private $dbConfiguration;


    /**
     * __Constructeur:
     * L'opérateur de portée appliqué au constructeur est "private"
     * Cela permet de s'assurer que la class ne puisse être instanciée que via la méthode Start()
     * Cette class a en effet la particularité d'être un singleton
     *
     * Le constructeur initialise les propriétés de la classe
     * @return void
     */
    private function __construct()
    {
        $this->environmentConfiguration = $this->getEnvironmentConfiguration();
    }


    /**
     * Méthode appelée par start()
     * La classe System applique le principe de fonctionnement du singleton
     * la méthode load() vérifie alors si la classe est déjà instanciée, et ne créer une nouvelle instance que dans le cas contraire
     * @return object Retourne l'instance de la classe.
     */
    private static function load()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }


    /**
     * La Méthode start() instancie la classe
     * Etablie l'environnement de développement
     * Déclenche la classe gérant le rootage et le system de page
     * Génère le système de vues avec TWIG
     * @return void
     */
    public static function start()
    {
        return self::load()
            ->setEnvironment()
            ->loadPageSystem()
            ->returnResult();
    }

    private function returnResult()
    {
        return $this->pageSystem->return;
    }


    /**
     * Méthode appelée par start()
     * Méthode permettant de configurer l'environnement de développement (dev, test, prod) en fonction
     * du fichier de configuration config-environment.twig
     * @return object Instance de la class courante
     */
    private function setEnvironment()
    {
        if($this->environmentConfiguration['server'] === 'dev'){
            define('DEBUG', true);
            define('ENV', 'DEV');
        } elseif($this->environmentConfiguration['server'] === 'test'){
            define('DEBUG', true);
            define('ENV', 'TEST');
        } elseif($this->environmentConfiguration['server'] === 'prod') {
            define('DEBUG', false);
            define('ENV', 'PROD');
        }

        if(defined('DEBUG') && DEBUG){
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
    	 	error_reporting(0);
        }

        return $this;
    }


    /**
     * Méthode appelée par start()
     * Initialise la propriété $pageSystem qui devient une instance de la class PageSystem
     * La classe PageSystem gère en principalement le chargement des controllers
     * @return object   Instance de la class courante
     */
    private function loadPageSystem()
    {
        $this->pageSystem = new PageSystem(
            $_GET
            , $_FILES
        );

        return $this;
    }


    /**
     * Méthode appelée par le constructeur
     * Elle est utilisée afin de parcourir le fichier YAML config_environment.yaml afin d'en extraire un tableau
     * Ce tableau contient la configuration du serveur à adopter (dev / test / prod)
     * Ce tableau est ensuite utilisé pour initialiser la propriété $environmentConfiguration
     * @return array   Tableau contenant la configuration du serveur à adopter (dev / test / prod)
     */
    private function getEnvironmentConfiguration()
    {
        return \Core\Configuration::parseYamlFile('environment/config_environment');
    }
}
