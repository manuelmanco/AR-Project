//
//  ARPcible.m
//  ar-ios
//
//  Created by charles duvert on 27/01/2016.
//  Copyright © 2016 Jordan. All rights reserved.
//

#import "ARPcible.h"

@implementation ARPcible

- (instancetype)init
{
    self = [super init];
    if (self) {
        double distance = [self distance];
        self.born = [NSDate date].timeIntervalSince1970;
        double t = [NSDate date].timeIntervalSince1970;
        double teta = (arc4random()%3-1) * sin(10 * t);
        double phi = (arc4random()%800 / 1000) * (arc4random()%3 - 1);
        self.x = distance * cos(teta);
        self.y = distance * sin(teta);
        self.z = distance * sin(phi);
        self.active = true;
        self.rayon = 0.3;
        self.vitesse = 1.0/60;
    
    }
    return self;
}

-(void)create{
   
}

-(void)maj{
    
    double teta = [self calculateTeta];
    double phi = [self calculatePhiWithTeta:teta];
    
    self.x = self.x - self.vitesse * cos(teta);
    self.y = self.y - self.vitesse * sin(teta);
    self.z = self.z - self.vitesse * sin(phi);
    
    
    
}

-(double)lifespan{
    double duree = self.dead - self.born;
    return duree;
}

-(bool)hitTestWithX: (double) x andWithY: (double) y{
    double r = [self rayon];
    double teta = [self calculateTeta];
    double phi = [self calculatePhiWithTeta:teta];
    NSArray *tabMonstre = [self positionOnScreenWithTeta: teta AndPhi: phi];
    double xMonstre = [tabMonstre[0] doubleValue];
    double yMonstre = [tabMonstre[1] doubleValue];
    double distMonstreTap = sqrt(pow(xMonstre-x,2)+pow(yMonstre-y,2));
    if (distMonstreTap <= r){
        self.active = false;
        self.vitesse = 0;
        //NSDate* now = [NSDate date];
        self.dead = [NSDate date].timeIntervalSince1970;
        return true;
    }else{
        return false;
    }
}

-(double)calculatePhiWithTeta: (double) teta{
    double phi = atan2(self.z, teta);
    return phi;
}

-(double)calculateTeta{
    double teta = atan2(self.y, self.x);
    return teta;
}

-(NSArray*) positionOnScreenWithTeta: (double)teta AndPhi: (double)phi{
    double x = [self distance] * sin(teta);
    double y = [self distance] * sin(phi);
    return @[@(x) , @(y)];
}

-(double)distance{
    double d = sqrt(pow(self.x, 2)+pow(self.y, 2)+pow(self.z, 2));
    return d;
}



-(double)rayon {
    self.rayon = kDefautRadius;
    double d = [self distance];
    double r = (kDefautdistancetophone*self.rayon)/d;
    return r;
}

@end
