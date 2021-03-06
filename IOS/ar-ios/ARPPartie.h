//
//  ARPPartie.h
//  ar-ios
//
//  Created by etudiant on 27/01/2016.
//  Copyright (c) 2016 Jordan. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface ARPPartie : NSObject

    @property NSDate *start;
    @property NSDate *end;
    @property NSMutableArray *shots;
    @property NSMutableArray *targets;
    @property int lives;
    @property int rayonEffectif;

    - (int) score;
    - (int) rayonEffectif;
    - (int) HowManyKilledTargets;
    - (int) HowManyTargetsVisibles;
    - (void) updateGame;
    - (BOOL) hitTestWithX: (double) x andWithY: (double) y;

@end
