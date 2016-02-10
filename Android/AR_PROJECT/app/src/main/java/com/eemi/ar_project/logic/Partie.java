package com.eemi.ar_project.logic;

import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.widget.ImageView;

import com.eemi.ar_project.R;

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
        for (Cible i: targets) {
            if (i.active){
                i.maj();
            }
        }
        return 0; // todo
    }
}
