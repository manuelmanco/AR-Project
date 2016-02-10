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
    
    UITapGestureRecognizer *l = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(handleTap:)];
    l.numberOfTouchesRequired = 1;
    [self.vrView addGestureRecognizer: l];
    
    self.vrView.compass.userInteractionEnabled = NO;
    self.vrView.userInteractionEnabled = YES;
    
    [super viewDidLoad];
    [self.partie updateGame];
    
    
}

- (void) handleTap:(UIGestureRecognizer*)g {
    double x = [g locationInView:self.vrView].x;
    double y = [g locationInView:self.vrView].y;
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
