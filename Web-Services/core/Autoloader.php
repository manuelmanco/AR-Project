<?php

/**
 * Class Autoloader
 *
 * Cette classe permet de charger l'ensemble des class du projet
 *
 * @author : Kévin Vacherot & Kévin Siow
 *
 */

namespace Core;

class Autoloader
{
    /**
     * Méthode permettant d'enregistrer la méthode autoload() comme autoloader
     * @return void
     */
    public static function register(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Méthode permettant d'ajouter à chaque élements de la class (namespaces + nom de class), une majuscule
     * @param string  $class_name  Nom de la class (Core\Autoloader par exemple)
     * @return string  Nom complet de la classe avec les namespaces et les noms de la classes avec une majuscule au début
     */
    private static function ucFirstClassName($class_name)
    {
        $class_components = explode('/', $class_name);

        foreach($class_components as $key => $value){
            $class_components[$key] = ucfirst($value);
        }

        return implode('/', $class_components);
    }


    /**
     * Méthode permettant de retourner le chemin de chaque class
     * Dans le chemin retourné, tout les niveau de dossier, mise à part le nom de la class, n'ont pas de majuscule au début
     * @param string  $class_name  Nom de la class (Core\Autoloader par exemple)
     * @return string  chemin final de la classe avec une majuscule uniquement au début du nom de classe
     */
    private static function returnClassPath($class_name)
    {
        $class_name = str_replace('\\', '/', $class_name);
        $path = dirname(__DIR__) . '/' . $class_name . '.php';
        $path_parts = explode('/', $path);
        $last = array_pop($path_parts);

        return strtolower(implode('/', $path_parts)) . '/' .ucfirst($last);
    }


    /**
     * Méthode permettant de charger toutes les classes
     * @param string  $class_name  Nom de la class (Core\Autoloader par exemple)
     * @return void
     */
    public static function autoload($class_name)
    {
        require self::returnClassPath(self::ucFirstClassName($class_name));
    }
}
