package com.eemi.ar_project.logic;

import java.util.ArrayList;

/**
 * Created by moufle on 27/01/16.
 */
public class Partie {
    public double start;
    public double end;
    public ArrayList <Tir> stats;
    public ArrayList <Cible> targets;
    public int lives;

    public int score() {
        return 0; // todo
    }

    public int visible() {
        for (ArrayList <Cible> i: targets) {

        }
        return 0; // todo
    }
}
