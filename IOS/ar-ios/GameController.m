//
//  GameController.m
//  ar-ios
//
//  Created by charles duvert on 10/02/2016.
//  Copyright © 2016 Jordan. All rights reserved.
//

#import "GameController.h"
#import "NZVRView.h"

@interface GameController ()

    @property (strong, nonatomic) IBOutlet NZVRView *vrView;

@end

@implementation GameController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

-(void)viewDidAppear:(BOOL)animated {
    [self.vrView start];
}

-(void)didRotateFromInterfaceOrientation:(UIInterfaceOrientation)fromInterfaceOrientation {
    [_vrView stop];
    [_vrView start];
}


@end
