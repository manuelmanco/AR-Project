//
//  ARPcible.h
//  ar-ios
//
//  Created by charles duvert on 27/01/2016.
//  Copyright © 2016 Jordan. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ARPcible : UIViewController

//POSITION
@property (nonatomic)  NSMutableArray *position;
//RAYON
@property (nonatomic) double rayon;
//VITESSE
@property (nonatomic) double vitesse;
//TYPE
@property (nonatomic) int type;
//ACTIVE
@property (nonatomic) BOOL active;
//DOMAGE
@property (nonatomic) int domage;
//BORN
@property NSDate *born;
//DEED
@property NSDate *deed;

@end
