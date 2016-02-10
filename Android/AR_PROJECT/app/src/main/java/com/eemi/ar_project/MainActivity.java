package com.eemi.ar_project;


import android.graphics.Typeface;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.ImageView;
import com.eemi.ar_project.logic.Cible;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        TextView score = (TextView) findViewById(R.id.textViewScoreText);
        Typeface face= Typeface.createFromAsset(getAssets(), "fonts/sui-generis-rg.ttf");
        score.setTypeface(face);
    }

    public void show(Cible i) {
        BitmapDrawable img = new BitmapDrawable(String.valueOf(getResources().getDrawable(R.drawable.background)));

        // Adds the view to the layout
        layout.addView(image);
    }
}
