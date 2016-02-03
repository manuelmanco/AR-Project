<?php

/**
 * Class Configuration
 *
 * Cette classe est utilisé pour parcourrir les fichiers de config et retourner leurs informations
 *
 * @author : Kévin Vacherot & Kévin Siow
 *
 */

namespace Core;

class Configuration
{
    /**
     * Méthode static appelé exclusivement depuis la Class MainController et la Class Models
     * Cette Méthode sert à parcourrir et retourner le contenu de fichiers de configuration écrits en YAML
     * @param string  $file  Nom du fichier de config à parcourrir
     * @return array Retourne le contenu du ficher YAML
     */
    public static function parseYamlFile($file)
    {
        return \App\Vendors\Yaml\Spyc::YAMLLoad(ROOT . '/configuration/' . $file . '.yaml');
    }
}
