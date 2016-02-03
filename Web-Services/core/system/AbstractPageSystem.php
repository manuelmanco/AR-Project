<?php

/**
 * Class AbstractSystem
 *
 *
 * @author : Kévin Vacherot & Kévin Siow
 *
 */

namespace Core\System;

abstract class AbstractPageSystem
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
     * Setter: Méthode utilisé pour Initialiser les propriétes $get, $post, $files, $request, $session
     * @param array  $get  Equivalent de la super globale $_GET
     * @param array  $files  Equivalent de la super globale $_FILES
     * @return void
     */
    protected function setGlobals($get, $files)
    {
        $this->get = $get;
        $this->files = $files;
    }
}
