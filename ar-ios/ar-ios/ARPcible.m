//
//  ARPcible.m
//  ar-ios
//
//  Created by charles duvert on 27/01/2016.
//  Copyright © 2016 Jordan. All rights reserved.
//

#import "ARPcible.h"

@interface ARPcible ()

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

@implementation ARPcible

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
