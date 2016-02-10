package com.eemi.ar_project;

import android.graphics.Typeface;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.TextView;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        TextView score = (TextView) findViewById(R.id.textViewScoreText);
        Typeface face= Typeface.createFromAsset(getAssets(), "fonts/sui-generis-rg.ttf");
        score.setTypeface(face);
    }
}
