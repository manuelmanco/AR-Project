package com.eemi.ar_project.logic;

/**
 * Created by moufle on 27/01/16.
 */
public class Tir {

    public boolean success;
    public Cible c;
    public double time;

    public double distance() {
        if (c == null) {
            return -1;
        }

        return Math.sqrt(Math.sqrt((c.x * c.x) + (c.y * c.y)) + (c.z * c.z));
    }

    public int nbDePoints() {
        return 0; // todo
    }
}

