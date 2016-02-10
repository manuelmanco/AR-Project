//
//  ARPcible.m
//  ar-ios
//
//  Created by charles duvert on 27/01/2016.
//  Copyright © 2016 Jordan. All rights reserved.
//

#import "ARPcible.h"

@implementation ARPcible

-(void)create{
    NSDate* now = [NSDate date];
    self.born = now;
}

-(void)maj{
    
}

-(double)lifespan{
    double duree = [self.dead timeIntervalSinceDate:self.born];
    return duree;
}

-(bool)hitTestWithX: (double) x andWithY: (double) y{
    double r = [self rayon];
    double phi = [self calculatePhi];
    double teta = [self calculateTetaWithPhi:phi];
    NSArray *tabMonstre = [self positionOnScreenWithTeta: teta AndPhi: phi];
    double xMonstre = [tabMonstre[0] doubleValue];
    double yMonstre = [tabMonstre[1] doubleValue];
    double distMonstreTap = sqrt(pow(xMonstre-x,2)+pow(yMonstre-y,2));
    if (distMonstreTap <= r){
        self.active = false;
        self.vitesse = 0;
        NSDate* now = [NSDate date];
        self.dead = now;
        return true;
    }else{
        return false;
    }
}


-(double)calculateTetaWithPhi: (double) phi{
    double teta = atan2(self.z, phi);
    return teta;
}

-(double)calculatePhi{
    double phi = atan2(self.y, self.x);
    return phi;
}

-(NSArray*) positionOnScreenWithTeta: (double)teta AndPhi: (double)phi{
    double x = kDefautdistancetophone * sin(phi);
    double y = kDefautdistancetophone * sin(teta);
    return @[@(x) , @(y)];
}

-(double)distance{
    double d = sqrt(pow(self.x, 2)+pow(self.y, 2)+pow(self.z, 2));
    return d;
}


-(double)rayon{
    self.rayon = kDefautRadius;
    double d = [self distance];
    double r = (kDefautdistancetophone*self.rayon)/d;
    return r;
}

@end
