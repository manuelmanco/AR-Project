//
//  ARPcible.h
//  ar-ios
//
//  Created by charles duvert on 27/01/2016.
//  Copyright © 2016 Jordan. All rights reserved.
//

#import <UIKit/UIKit.h>

#define kDefautdistancetophone 30
#define kDefautRadius 30

@interface ARPcible : UIViewController
//POSITION
@property (nonatomic) double x;
@property (nonatomic) double y;
@property (nonatomic) double z;
//RAYON
@property (nonatomic) double rayon;
//ACTIVE
@property (nonatomic) BOOL active;


//VITESSE
@property (nonatomic) double vitesse;
//TYPE
@property (nonatomic) int type;
//DOMAGE
@property (nonatomic) int domage;



//BORN
@property double born;
//DEED
@property double dead;



-(NSArray*)positionOnScreenWithTeta: (double) teta AndPhi: (double) phi;
-(bool)hitTestWithX: (double) x andWithY: (double) y;
-(void)maj;
-(double)distance;

@end
