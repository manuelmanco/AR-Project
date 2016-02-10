package com.eemi.ar_project.controller;

import com.eemi.ar_project.logic.Cible;
import com.eemi.ar_project.logic.Partie;

/**
 * Created by moufle on 03/02/16.
 */
public class GameController {
    Partie game;
    public double phy;
    public double theta;

    public void gameInit()
    {
        // abc
    }

    public void gameEnd()
    {

    }

    public void gameStep()
    {

    }

    public void shoot()
    {
        // appel la fonction HitTest sur toutes les cibles
        for (Cible c : game.targets)
        {
            c.hitTest(,);
        }
    }
}
