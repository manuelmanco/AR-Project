//
//  NZVRView.h
//  VR
//
//  Created by Nicolas Zinovieff on 2016/02/09.
//  Copyright © 2016 Nicolas Zinovieff. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "NZCompassView.h"

#define DEGREES_TO_RADIANS (M_PI/180.0)
#define RADIANS_TO_DEGREES (180.0/M_PI)

@interface NZVRView : UIView
@property (weak, nonatomic) IBOutlet UIViewController *container;
@property (weak, nonatomic) IBOutlet NZCompassView *compass;

- (void) cleanup;
- (void) start;
- (void) stop;

@end
