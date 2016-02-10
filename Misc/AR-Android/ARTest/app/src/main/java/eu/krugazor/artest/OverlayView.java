package eu.krugazor.artest;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.hardware.Camera;
import android.hardware.Sensor;
import android.hardware.SensorEvent;
import android.hardware.SensorEventListener;
import android.hardware.SensorManager;
import android.view.Surface;
import android.view.View;

/**
 * Created by Nicolas Zinovieff on 2016/02/09.
 */
public class OverlayView extends View implements SensorEventListener {

    public static final String DEBUG_TAG = "OverlayView Log";
    private final Paint targetPaint;
    String accelData = "Accelerometer Data";
    String compassData = "Compass Data";
    String gyroData = "Gyro Data";
    public ArDisplayView arView = null;

    public OverlayView(Context context) {
        super(context);

        SensorManager sensors = (SensorManager) context.getSystemService(Context.SENSOR_SERVICE);
        Sensor accelSensor = sensors.getDefaultSensor(Sensor.TYPE_ACCELEROMETER);
        Sensor compassSensor = sensors.getDefaultSensor(Sensor.TYPE_MAGNETIC_FIELD);
        Sensor gyroSensor = sensors.getDefaultSensor(Sensor.TYPE_GYROSCOPE);

        boolean isAccelAvailable = sensors.registerListener(this, accelSensor, SensorManager.SENSOR_DELAY_NORMAL);
        boolean isCompassAvailable = sensors.registerListener(this, compassSensor, SensorManager.SENSOR_DELAY_NORMAL);
        boolean isGyroAvailable = sensors.registerListener(this, gyroSensor, SensorManager.SENSOR_DELAY_NORMAL);

        this.targetPaint = new Paint();
        targetPaint.setColor(Color.argb(255,255,0,0));
    }

    @Override
    protected void onDraw(Canvas canvas) {
        super.onDraw(canvas);

        Paint contentPaint = new Paint(Paint.ANTI_ALIAS_FLAG);
        contentPaint.setTextAlign(Paint.Align.CENTER);
        contentPaint.setTextSize(20);
        contentPaint.setColor(Color.RED);
        canvas.drawText(accelData, canvas.getWidth()/2, 8, contentPaint);
        canvas.drawText(compassData, canvas.getWidth()/2, 28, contentPaint);
        canvas.drawText(gyroData, canvas.getWidth()/2, 48, contentPaint);

        if( arView == null ) { return; } // skip camera stuff since we don't have a camera
        Camera.Parameters params = arView.mCamera.getParameters();

        int rotation = arView.mActivity.getWindowManager().getDefaultDisplay().getRotation();
        int degrees = 0;
        switch (rotation) {
            case Surface.ROTATION_0:
                degrees = 0;
                break;
            case Surface.ROTATION_90:
                degrees = 90;
                break;
            case Surface.ROTATION_180:
                degrees = 180;
                break;
            case Surface.ROTATION_270:
                degrees = 270;
                break;
        }


        float verticalFOV = params.getVerticalViewAngle();
        float horizontalFOV = params.getHorizontalViewAngle();

        float orientation[] = handsetOrientation();
        // use roll for screen rotation
        canvas.rotate((float)(0.0f- Math.toDegrees(orientation[2]) - degrees));

        // Example : paint a point at the north point

        // Translate, but normalize for the FOV of the camera -- basically, pixels per degree, times degrees == pixels
        float dx = (float) ( (canvas.getWidth()/ horizontalFOV) * (Math.toDegrees(orientation[0])-0));
        float dy = (float) ( (canvas.getHeight()/ verticalFOV) * Math.toDegrees(orientation[1])) ;

        // wait to translate the dx so the horizon doesn't get pushed off
        canvas.translate(0.0f, 0.0f-dy);

        // make our line big enough to draw regardless of rotation and translation
        canvas.drawLine(0f - canvas.getHeight(), canvas.getHeight()/2, canvas.getWidth()+canvas.getHeight(), canvas.getHeight()/2, targetPaint);


        // now translate the dx
        canvas.translate(0.0f-dx, 0.0f);

        // draw our point -- we've rotated and translated this to the right spot already
        canvas.drawCircle(canvas.getWidth()/2, canvas.getHeight()/2, 8.0f, targetPaint);
    }

    @Override
    public void onSensorChanged(SensorEvent event) {
        StringBuilder msg = new StringBuilder(event.sensor.getName()).append(" ");
        for(float value: event.values)
        {
            msg.append("[").append(value).append("]");
        }

        switch(event.sensor.getType())
        {
            case Sensor.TYPE_ACCELEROMETER:
                accelData = msg.toString();
                lastAccelerometer = event.values;
                break;
            case Sensor.TYPE_GYROSCOPE:
                gyroData = msg.toString();
                break;
            case Sensor.TYPE_MAGNETIC_FIELD:
                compassData = msg.toString();
                lastCompass = event.values;
                break;
        }

        this.invalidate();
    }

    @Override
    public void onAccuracyChanged(Sensor sensor, int accuracy) {

    }

    private float[] lastAccelerometer = null;
    private float[] lastCompass = null;

    public float[] handsetOrientation() {
        // compute rotation matrix
        float rotation[] = new float[9];
        float identity[] = new float[9];
        boolean gotRotation = SensorManager.getRotationMatrix(rotation,
                identity, lastAccelerometer, lastCompass);

        if (gotRotation) {
            float cameraRotation[] = new float[9];
            // remap such that the camera is pointing straight down the Y axis
            SensorManager.remapCoordinateSystem(rotation, SensorManager.AXIS_X,
                    SensorManager.AXIS_Z, cameraRotation);

            // orientation vector
            float orientation[] = new float[3];
            SensorManager.getOrientation(cameraRotation, orientation);
            return orientation;
         }

        return null;
    }
}