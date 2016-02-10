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
        
        NSDate* now = [NSDate date];
        self.born = now;
        self.x = rand();
        self.y = rand();
        self.z = rand();
        self.active = true;
        self.rayon = 0.3;
        self.vitesse = 1.0;
    
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
    double duree = [self.dead timeIntervalSinceDate:self.born];
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
        NSDate* now = [NSDate date];
        self.dead = now;
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
