//
//  NZVRView.m
//  VR
//
//  Created by Nicolas Zinovieff on 2016/02/09.
//  Copyright © 2016 Nicolas Zinovieff. All rights reserved.
//

#import "NZVRView.h"
#import <CoreMotion/CoreMotion.h>
#import <AVFoundation/AVFoundation.h>
#import <OpenGLES/EAGL.h>
#import <OpenGLES/ES1/gl.h>
#import <OpenGLES/ES1/glext.h>

#pragma mark -
#pragma mark Math utilities declaration

typedef float mat4f_t[16];	// 4x4 matrix in column major order
typedef float vec4f_t[4];	// 4D vector

// Creates a projection matrix using the given y-axis field-of-view, aspect ratio, and near and far clipping planes
void createProjectionMatrix(mat4f_t mout, float fovy, float aspect, float zNear, float zFar);

// Matrix-vector and matrix-matricx multiplication routines
void multiplyMatrixAndVector(vec4f_t vout, const mat4f_t m, const vec4f_t v);
void multiplyMatrixAndMatrix(mat4f_t c, const mat4f_t a, const mat4f_t b);

// Initialize mout to be an affine transform corresponding to the same rotation specified by m
void transformFromCMRotationMatrix(vec4f_t mout, const CMRotationMatrix *m);

@interface NZVRView () {
    mat4f_t projectionTransform;
    mat4f_t cameraTransform;
    vec4f_t *placesOfInterestCoordinates;
    
    BOOL isStarted;
}

@property(strong, nonatomic) UIView *captureView;
@property(strong, nonatomic) AVCaptureSession *captureSession;
@property(strong, nonatomic) AVCaptureVideoPreviewLayer *captureLayer;
@property(strong, nonatomic) CADisplayLink *displayLink;
@property(strong, nonatomic) CMMotionManager *motionManager;

- (void)startDeviceMotion;
- (void)stopDeviceMotion;

- (void)startDisplayLink;
- (void)stopDisplayLink;
- (void)onDisplayLink:(id)sender;

@end

@implementation NZVRView
- (void)cleanup
{
    [self stop];
    [self.captureView removeFromSuperview];
    self.captureView = nil;
    if (placesOfInterestCoordinates != NULL) {
        free(placesOfInterestCoordinates);
    }
}

- (void)start
{
    if(isStarted)
        return;
    
    [self startCameraPreview];
    [self startDeviceMotion];
    [self startDisplayLink];
    
    isStarted = YES;
}

- (void)stop
{
    [self stopCameraPreview];
    [self stopDeviceMotion];
    [self stopDisplayLink];
    
    isStarted = NO;
}

- (void)initialize
{
    self.captureView = [[UIView alloc] initWithFrame:self.bounds];
    self.captureView.bounds = self.bounds;
    [self.captureView setAutoresizingMask:UIViewAutoresizingFlexibleHeight|UIViewAutoresizingFlexibleWidth];
    [self addSubview:self.captureView];
    [self sendSubviewToBack:self.captureView];
    
    isStarted = NO;
}

- (void)startCameraPreview
{
    AVCaptureDevice* camera = [AVCaptureDevice defaultDeviceWithMediaType:AVMediaTypeVideo];
    if (camera == nil) {
        return;
    }
    
    self.captureSession = [[AVCaptureSession alloc] init];
    AVCaptureDeviceInput *newVideoInput = [[AVCaptureDeviceInput alloc] initWithDevice:camera error:nil];
    [self.captureSession addInput:newVideoInput];
    
    self.captureLayer = [[AVCaptureVideoPreviewLayer alloc] initWithSession:self.captureSession];
    self.captureLayer.frame = self.captureView.bounds;
    if(self.container.interfaceOrientation == UIDeviceOrientationLandscapeRight) {
        // [self.captureLayer setOrientation:AVCaptureVideoOrientationLandscapeLeft];
        self.captureLayer.connection.videoOrientation = AVCaptureVideoOrientationLandscapeLeft;
        for(AVCaptureOutput *output in [self.captureSession outputs]) {
            for(AVCaptureConnection *connection in output.connections) {
                connection.videoOrientation = AVCaptureVideoOrientationLandscapeLeft;
            }
        }
    } else if(self.container.interfaceOrientation == UIDeviceOrientationLandscapeLeft) {
        // [self.captureLayer setOrientation:AVCaptureVideoOrientationLandscapeRight];
        self.captureLayer.connection.videoOrientation = AVCaptureVideoOrientationLandscapeRight;
        for(AVCaptureOutput *output in [self.captureSession outputs]) {
            for(AVCaptureConnection *connection in output.connections) {
                connection.videoOrientation = AVCaptureVideoOrientationLandscapeRight;
            }
        }
    }
    [self.captureLayer setVideoGravity:AVLayerVideoGravityResizeAspectFill];
    [self.captureView.layer addSublayer:self.captureLayer];
    
    // Initialize projection matrix
    createProjectionMatrix(projectionTransform, 60.0f*DEGREES_TO_RADIANS, 1.0f*self.bounds.size.height/self.bounds.size.width, 0.25f, 1000.0f);
    
    // Start the session. This is done asychronously since -startRunning doesn't return until the session is running.
    dispatch_async(dispatch_get_global_queue(DISPATCH_QUEUE_PRIORITY_DEFAULT, 0), ^{
        [self.captureSession startRunning];
    });
}

- (void)stopCameraPreview
{
    [self.captureSession stopRunning];
    [self.captureLayer removeFromSuperlayer];
    self.captureSession = nil;
    self.captureLayer = nil;
}

- (void)startDeviceMotion
{
    self.motionManager = [[CMMotionManager alloc] init];
    
    // Tell CoreMotion to show the compass calibration HUD when required to provide true north-referenced attitude
    self.motionManager.showsDeviceMovementDisplay = YES;
    
    
    self.motionManager.deviceMotionUpdateInterval = .10; // 10 times a second?
    
    // New in iOS 5.0: Attitude that is referenced to true north
    [self.motionManager startDeviceMotionUpdatesUsingReferenceFrame:CMAttitudeReferenceFrameXTrueNorthZVertical];
}

- (void)stopDeviceMotion
{
    [self.motionManager stopDeviceMotionUpdates];
    self.motionManager = nil;
}

- (void)startDisplayLink
{
    self.displayLink = [CADisplayLink displayLinkWithTarget:self selector:@selector(onDisplayLink:)];
    [self.displayLink setFrameInterval:1];
    [self.displayLink addToRunLoop:[NSRunLoop currentRunLoop] forMode:NSDefaultRunLoopMode];
}

- (void)stopDisplayLink
{
    [self.displayLink invalidate];
    self.displayLink = nil;
}

- (void)onDisplayLink:(id)sender
{
    CMDeviceMotion *d = self.motionManager.deviceMotion;
    if (d != nil) {
        CMRotationMatrix r = d.attitude.rotationMatrix;
        
        // Get the heading.
        double heading = fmod((((180.0 * d.attitude.yaw) / M_PI) + 180.0), 360.0);
        
        if(self.compass != nil)
            self.compass.heading = heading;
        
        transformFromCMRotationMatrix(cameraTransform, &r);
        
        [self setNeedsDisplay];
    }
}

- (void)drawRect:(CGRect)rect
{
    if (placesOfInterestCoordinates == nil) {
        return;
    }
    
    mat4f_t projectionCameraTransform, tmpCamTransform;
    mat4f_t deviceRotation;
    if(self.container.interfaceOrientation == UIDeviceOrientationLandscapeRight) {
        deviceRotation[0] = 0.0f; // m11
        deviceRotation[1] = -1.0f; // m21
        deviceRotation[2] = 0.0f; // m31
        deviceRotation[3] = 0.0f;
        
        deviceRotation[4] = 1.0; // m12
        deviceRotation[5] = 0.0f; // m22
        deviceRotation[6] = 0.0f; // m32
        deviceRotation[7] = 0.0f;
        
        deviceRotation[8] = 0.0f; // m13
        deviceRotation[9] = 0.0f; // m23
        deviceRotation[10] = 1.0f; // m33
        deviceRotation[11] = 0.0f;
        
        deviceRotation[12] = 0.0f;
        deviceRotation[13] = 0.0f;
        deviceRotation[14] = 0.0f;
        deviceRotation[15] = 1.0f;
    } else if(self.container.interfaceOrientation == UIDeviceOrientationLandscapeLeft) {
        deviceRotation[0] = 0.0f; // m11
        deviceRotation[1] = 1.0f; // m21
        deviceRotation[2] = 0.0f; // m31
        deviceRotation[3] = 0.0f;
        
        deviceRotation[4] = -1.0; // m12
        deviceRotation[5] = 0.0f; // m22
        deviceRotation[6] = 0.0f; // m32
        deviceRotation[7] = 0.0f;
        
        deviceRotation[8] = 0.0f; // m13
        deviceRotation[9] = 0.0f; // m23
        deviceRotation[10] = 1.0f; // m33
        deviceRotation[11] = 0.0f;
        
        deviceRotation[12] = 0.0f;
        deviceRotation[13] = 0.0f;
        deviceRotation[14] = 0.0f;
        deviceRotation[15] = 1.0f;
    }
    multiplyMatrixAndMatrix(tmpCamTransform, deviceRotation, cameraTransform);
    multiplyMatrixAndMatrix(projectionCameraTransform, projectionTransform, tmpCamTransform);
    
    /* for the center of whatever we're looking at */
    /*
    vec4f_t v;
    multiplyMatrixAndVector(v, projectionCameraTransform, placesOfInterestCoordinates[i]);
    
    float x = (v[0] / v[3] + 1.0f) * 0.5f;
    float y = (v[1] / v[3] + 1.0f) * 0.5f;
    if (v[2] < 0.0f) {
        poi.view.center = CGPointMake(x*self.bounds.size.width, self.bounds.size.height-y*self.bounds.size.height);
     */

}

- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        [self initialize];
    }
    return self;
}

- (id)initWithCoder:(NSCoder *)aDecoder
{
    self = [super initWithCoder:aDecoder];
    if (self) {
        [self initialize];
    }
    return self;
}


@end

#pragma mark -
#pragma mark Math utilities definition

// Creates a projection matrix using the given y-axis field-of-view, aspect ratio, and near and far clipping planes
void createProjectionMatrix(mat4f_t mout, float fovy, float aspect, float zNear, float zFar)
{
    float f = 1.0f / tanf(fovy/2.0f);
    
    mout[0] = f / aspect;
    mout[1] = 0.0f;
    mout[2] = 0.0f;
    mout[3] = 0.0f;
    
    mout[4] = 0.0f;
    mout[5] = f;
    mout[6] = 0.0f;
    mout[7] = 0.0f;
    
    mout[8] = 0.0f;
    mout[9] = 0.0f;
    mout[10] = (zFar+zNear) / (zNear-zFar);
    mout[11] = -1.0f;
    
    mout[12] = 0.0f;
    mout[13] = 0.0f;
    mout[14] = 2 * zFar * zNear /  (zNear-zFar);
    mout[15] = 0.0f;
    
}

// Matrix-vector and matrix-matricx multiplication routines
void multiplyMatrixAndVector(vec4f_t vout, const mat4f_t m, const vec4f_t v)
{
    vout[0] = m[0]*v[0] + m[4]*v[1] + m[8]*v[2] + m[12]*v[3];
    vout[1] = m[1]*v[0] + m[5]*v[1] + m[9]*v[2] + m[13]*v[3];
    vout[2] = m[2]*v[0] + m[6]*v[1] + m[10]*v[2] + m[14]*v[3];
    vout[3] = m[3]*v[0] + m[7]*v[1] + m[11]*v[2] + m[15]*v[3];
}

void multiplyMatrixAndMatrix(mat4f_t c, const mat4f_t a, const mat4f_t b)
{
    uint8_t col, row, i;
    memset(c, 0, 16*sizeof(float));
    
    for (col = 0; col < 4; col++) {
        for (row = 0; row < 4; row++) {
            for (i = 0; i < 4; i++) {
                c[col*4+row] += a[i*4+row]*b[col*4+i];
            }
        }
    }
}

// Initialize mout to be an affine transform corresponding to the same rotation specified by m
void transformFromCMRotationMatrix(vec4f_t mout, const CMRotationMatrix *m)
{
    mout[0] = (float)m->m11;
    mout[1] = (float)m->m21;
    mout[2] = (float)m->m31;
    mout[3] = 0.0f;
    
    mout[4] = (float)m->m12;
    mout[5] = (float)m->m22;
    mout[6] = (float)m->m32;
    mout[7] = 0.0f;
    
    mout[8] = (float)m->m13;
    mout[9] = (float)m->m23;
    mout[10] = (float)m->m33;
    mout[11] = 0.0f;
    
    mout[12] = 0.0f;
    mout[13] = 0.0f;
    mout[14] = 0.0f;
    mout[15] = 1.0f;
}
