//
//  NZCompassView.m
//  ADP-VR
//
//  Created by Nico Zino on 2012/10/11.
//  Copyright (c) 2012 Nico Zino. All rights reserved.
//

#import "NZCompassView.h"
#import <QuartzCore/QuartzCore.h>
#import "NZVRView.h"

@interface NZCompassView ()
@property(strong,nonatomic) UIImageView *rotatingCompassView;
@end

@implementation NZCompassView
@synthesize rotatingCompassView;
@synthesize heading = _heading;

- (void) initCompass:(CGRect) frame {
    self.rotatingCompassView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"compass"]];
    if(frame.size.height > frame.size.width)
        self.rotatingCompassView.frame = CGRectMake(0, (frame.size.height - frame.size.width) * .5, frame.size.width, frame.size.width);
    else
        self.rotatingCompassView.frame = CGRectMake((frame.size.width - frame.size.height) * .5, 0, frame.size.height, frame.size.height);
    
    [self.rotatingCompassView setBackgroundColor:[UIColor clearColor]];
    [self setBackgroundColor:[UIColor clearColor]];
    // [self addSubview:self.rotatingCompassView];
    [self.layer addSublayer:self.rotatingCompassView.layer];
    self.rotatingCompassView.layer.shadowOpacity = .7;
    self.rotatingCompassView.layer.shadowRadius = 6.0;
    self.rotatingCompassView.layer.shadowOffset = CGSizeMake(0, 4.0);
    self.layer.transform = CATransform3DTranslate(
                                                  CATransform3DMakeRotation(M_PI_4, 1, 0, 0),
                                                  0, (frame.size.height*.30), (frame.size.height*.30));
}

- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
    }
    return self;
}

- (instancetype)initWithCoder:(NSCoder *)coder
{
    self = [super initWithCoder:coder];
    if (self) {
        [self initCompass:self.frame];
    }
    return self;
}

- (void) setHeading:(CGFloat)heading {
    _heading = heading;
    // NSLog(@"%.2f", heading);
    
    self.rotatingCompassView.transform = CGAffineTransformMakeRotation(heading*DEGREES_TO_RADIANS);
}

@end
