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
        if (cible.active == true){
            ciblesCount++;
        }
    }
    //calculate number of actives targets in self.targets and return this number
    return ciblesCount;
}

- (void) updateGame {
    //generate new targets or not depending of number of lives player has and time of the game
    //do something
}

- (BOOL) hitTestWithPhi:(double)phi andWithTeta:(double)teta {
    //check if a target is present in direction the player pushed
    return true;
}

@end
