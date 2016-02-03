<?php

/**
 * Class PageSystem
 *
 *
 * @author : Kévin Vacherot & Kévin Siow
 *
 */

namespace Core\System;

class PageSystem extends AbstractPageSystem
{
    /**
     * @var array  Equivalent de la super globale $_GET
     */
    protected $get;

    /**
     * @var array  Equivalent de la super globale $_FILES
     */
    protected $files;

    /**
     * @var array  Instance de la class Developper
     */
    protected $developper;

    /**
     * @var array  retour du call api
     */
    public $return = array();


    /**
     * __Constructeur:
     * Le constructeur initialise les propriétés de la classe
     * le contructeur déclenche la méthode authentificateDevelopper permettant d'authentifier le developper
     * Si le developper est authentifié, un token est généré et retourné, celui-ci lui permettra d'effectuer les call api
     * le constructeur déclenche la méthode callMethod, permettant d'éxecuter la bonne méthde dans le bon controller
     * @param array  $get  Equivalent de la super globale $_GET
     * @param array  $files  Equivalent de la super globale $_FILES
     * @return void
     */
    public function __construct($get, $files)
    {
        $this->get = $get;
        $this->files = $files;
        $this->developper = new \App\Models\Developper();

        // Demande d'authentification et de génération de token
        if(isset($this->get['methode']) && $this->get['methode'] === 'authentificateDevelopper'){
            if(isset($this->get['params']['developper_name']) && isset($this->get['params']['developper_secret'])){
                $developper_token = $this->authentificateDevelopper($this->get['params']['developper_name'], $this->get['params']['developper_secret']);
                if($developper_token !== false){
                    $this->return['developper_token'] = $developper_token;
                } else {
                    $this->return['error']['unknown_developper'] = true;
                }
            } else {
                $this->return['error']['no_auth_parameters'] = true;
            }
        // Call api avec developper_token
        } else {
            if(isset($this->get['developper_token'])){
                if($this->isValidToken($this->get['developper_token'])){
                    $module = isset($this->get['module']) && !empty($this->get['module']) ? $this->get['module'] : false;
                    $action = isset($this->get['action']) && !empty($this->get['action']) ? $this->get['action'] : false;
                    if(!$module){
                        $this->return['error']['no_module_called'] = true;
                    }
                    if(!$action){
                        $this->return['error']['no_method_called'] = true;
                    }
                    $module && $action ? $this->callMethod($module, $action) : null;
                } else {
                    $this->return['error']['invalid_token'] = true;
                }
            } else {
                $this->return['error']['no_developper_token_detected'] = true;
            }
        }
    }


    /**
     * Méthode permettant d'authentifier le developper
     * Si le developper a les droits pour effectuer des call api, on initialise la propriété $authentificated à true,
     * sinon à false
     * @param string  $mail  mail du developper
     * @param string  $password  password du developper
     * @return void
     */
    private function authentificateDevelopper($developper_name, $developper_secret)
    {
        if($this->developper->apiDevelopperExist(array('developper_name' => $developper_name, 'developper_secret' => $developper_secret))){
            $developper_token = md5(uniqid(mt_rand()));
            $this->developper->generateNewDevelopperToken($developper_name, $developper_secret, $developper_token);

            $return = $developper_token;
        } else {
            $return = false;
        }

        return $return;
    }


    /**
     * Méthode appelée par le contrôleur
     * Elle permet de déclencher le bon controller et la bonne méthode, suivant les params du call api
     * @param string  $module  Nom du controller à instancier correspondant à $this->get['module']
     * @param string  $action  Nom de la méthode à exécuter correspondant à $this->get['action']
     * @return void
     */
    private function isValidToken($developper_token)
    {

        return $this->developper->apiDevelopperExist(array('developper_token' => $developper_token));
    }


    /**
     * Méthode appelée par le contrôleur
     * Elle permet de déclencher le bon controller et la bonne méthode, suivant les params du call api
     * @param string  $module  Nom du controller à instancier correspondant à $this->get['module']
     * @param string  $action  Nom de la méthode à exécuter correspondant à $this->get['action']
     * @return void
     */
    private function callMethod($module, $action)
    {
        $controllerName = ucfirst($module) . 'Controller';
        $moduleController = 'app\controllers\\' . $controllerName;
        $pathModuleController = str_replace('\\', '/', $moduleController . '.php');

        $controller = is_file($pathModuleController) ? new $moduleController() : $this->return['module_no_exist'] = true;
        is_file($pathModuleController) ? $controller->setGlobals($this->get, $this->files) : null;

        $this->return = method_exists($controller, $action) ? $controller->$action() : array('method_no_exist' => true);
    }
}
