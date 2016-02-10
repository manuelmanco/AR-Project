//
//  ViewController.m
//  VR
//
//  Created by Nicolas Zinovieff on 2016/02/09.
//  Copyright © 2016 Nicolas Zinovieff. All rights reserved.
//

#import "ViewController.h"
#import "NZVRView.h"

@interface ViewController ()
@property (strong, nonatomic) IBOutlet NZVRView *vrView;

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
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
