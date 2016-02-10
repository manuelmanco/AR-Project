package com.eemi.ar_project.logic;

import java.util.Date;

/**
 * Created by moufle on 27/01/16.
 */
public class Cible {
    public double x;
    public double y;
    public double z;
    public double rayon = 30;
    public double screenDistance = 30;
    public double vitesse;
    public int type;
    public boolean active;
    public int damage;
    public double born;
    public double dead;

    public void maj() {
        z += vitesse;


    }

    public double lifespan() {
        if (active) {
            return dead - born;
        } else {
            return new Date().getTime() - born;
        }
    }

    public double distance() {
        return Math.sqrt((x * x) + (y * y) + (z * z));
    }

    // Calcul de r'
    public double size() {
        double d = distance();

        return (screenDistance * rayon) / d;
    }

    public boolean hitTest(double a, double b) {

        double ecart = Math.sqrt((a - x) * (a - x) + (b - y) * (b - y));
        double rprime = size();

        return ecart <= rprime;

    }


}
