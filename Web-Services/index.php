<?php

/**
 * Inclusion des constantes du projet
 */
require_once 'configuration/constantes.php';

/**
 * Inclusion de l'autoloader
 */
require_once 'core/Autoloader.php';

/**
 * Lancement de l'autoloader pour charger toutes les classes du projet
 */
Core\Autoloader::register();

/**
 * Définition des domaines autorisés à communiquer avec l'api
 */
App\Services\AccessControl::setAuthDomains(array('http://localhost:8888'));

/**
 * JSON encode du retour
 */
//header('Content-type: application/json; charset=UTF-8');
echo json_encode(Core\System\System::start());
