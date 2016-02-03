<?php

/**
 * Class AccessControl
 *
 * Cette classe gère les control d'acces à l'api
 *
 * @author : Kévin Vacherot & Kévin Siow
 *
 */

namespace App\Services;

class AccessControl
{
    /**
     * Méthode permettant de définir les domaines autorisés à communiquer avec l'api
     * @param array  $allowedDomains  nom des domaines autorisés à communiquer avec l'api
     * @return void
     */
    public static function setAuthDomains($allowedDomains = null)
	{
        // On vérifie s'il s'agit d'une requête cross-domain
        if (!isset($_SERVER['HTTP_ORIGIN'])) {
            exit;
        }

        // On indique et vérifie les domaines autorisés à communiquer avec l'api
        if (!in_array($_SERVER['HTTP_ORIGIN'], $allowedDomains)) {
            exit;
        }

        $origin = is_null($allowedDomains) ? '*' : $_SERVER['HTTP_ORIGIN'];

        header("Access-Control-Allow-Origin: " . $origin);
        header("Access-Control-Allow-Credentials: true"); //On indique si on attend des credential requests (Cookies, Authentication, SSL certificates)
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Origin");
	}
}
