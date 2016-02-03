//
//  ARPTir.h
//  ar-ios
//
//  Created by etudiant on 27/01/2016.
//  Copyright (c) 2016 Jordan. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "ARPcible.h"

@interface ARPTir : NSObject
@property bool success;
@property ARPcible *cible;
@property NSDate *time;

- (double) distance;
- (int) NmbreDePoints;
@end
