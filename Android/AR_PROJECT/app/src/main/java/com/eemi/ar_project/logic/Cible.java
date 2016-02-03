package com.eemi.ar_project.logic;

import java.util.Date;

/**
 * Created by moufle on 27/01/16.
 */
public class Cible {
    public double x;
    public double y;
    public double z;
    public double rayon;
    public double vitesse;
    public int type;
    public boolean active;
    public int damage;
    public double born;
    public double dead;

    public void maj() {
        
    }

    public double lifespan() {
        if (active) {
            return dead - born;
        } else {
            return new Date().getTime() - born;
        }

    }

    public boolean hitTest( double phi, double theta) {
        return false; // TODO: 1/27/2016
    }
}
