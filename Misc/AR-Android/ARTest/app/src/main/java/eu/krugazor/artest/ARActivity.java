package eu.krugazor.artest;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.FrameLayout;

public class ARActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_ar);

        FrameLayout arViewPane = (FrameLayout) findViewById(R.id.ar_view_pane);

        ArDisplayView arDisplay = new ArDisplayView(getApplicationContext(), this);
        arViewPane.addView(arDisplay);

        OverlayView arContent = new OverlayView(getApplicationContext());
        arViewPane.addView(arContent);

        arDisplay.overlay = arContent;
        arContent.arView = arDisplay;
    }
}
