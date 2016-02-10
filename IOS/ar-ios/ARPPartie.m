//
//  ARPPartie.m
//  ar-ios
//
//  Created by etudiant on 27/01/2016.
//  Copyright (c) 2016 Jordan. All rights reserved.
//

#import "ARPPartie.h"
#import "ARPcible.h"
#import "ARPTir.h"

@implementation ARPPartie

- (int) score {
    //calculate score with self.shots and return
    return 1;
}

- (int) HowManyKilledTargets {
    int killedTargets = 0;
    for(ARPTir *tir in self.shots){
        if(tir.success == true){
            killedTargets++;
        }
    }
    return 1;
}

- (int) HowManyTargetsVisibles {
    int ciblesCount = 0;
    for(ARPcible *cible in self.targets){
        if (cible.active == true) {
            ciblesCount++;
        }
    }
    //calculate number of actives targets in self.targets and return this number
    return ciblesCount;
}

- (void) updateGame {
    
    //generate new targets or not depending of number of lives player has and time of the game
    //do something
    
    for (ARPcible *cible in self.targets){
        if(cible.active == true){
            [cible maj];
            double distance = [cible distance];
            if(distance < 1.0){
                cible.dead = [NSDate date].timeIntervalSince1970;
                cible.active = false;
                self.lives--;
            }
        }
    }
    
    if([self HowManyTargetsVisibles] == 0){
        //generate one new target
        ARPcible* cible = [ARPcible new];
        [self.targets addObject:cible];
    }
    
    if(self.lives > 0){
        dispatch_async(dispatch_get_main_queue(), ^{
            [self updateGame];
        });
    }else{
        //vue defaite
    }
}

- (BOOL) hitTestWithX:(double) x andWithY:(double) y {
    
    //check if a target is present in direction the player pushed
    for(ARPcible *c in self.targets){
        
        bool touch = [c hitTestWithX: x andWithY: y];
        
        if (touch == true) {
            //Cible morte créer un tir
        }
        
    }
    
    return true;
    
}

@end
