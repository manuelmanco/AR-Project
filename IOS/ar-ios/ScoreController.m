//
//  ScoreController.m
//  ar-ios
//
//  Created by charles duvert on 10/02/2016.
//  Copyright © 2016 Jordan. All rights reserved.
//

#import "ScoreController.h"

@interface ScoreController ()

    @property (weak, nonatomic) IBOutlet UITextView *textScore;
    @property (weak, nonatomic) IBOutlet UIButton *ButtonWeekly;
    @property (weak, nonatomic) IBOutlet UIButton *MonthlyButton;
    @property (weak, nonatomic) IBOutlet UIButton *ButtonAlltime;


@end

@implementation ScoreController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    NSError* error = nil;
    NSData *jsonData2 = [NSData dataWithContentsOfURL:[NSURL URLWithString:@"http://vacherot.etudiant-eemi.com/perso/ar_project/?developper_token=6c3a235a40020e288792b158896db4bc&module=score&action=getHighScores&params%5Blimit%5D=0&params%5Boffset%5D=10&params%5Bperiod%5D=allTime"]];
    
    id jsonObjects2 = [NSJSONSerialization JSONObjectWithData:jsonData2 options:NSJSONReadingMutableContainers error:&error];
    if (error) {
        
        NSLog(@"error");
        
    } else {
        NSArray *player = [jsonObjects2  objectForKey:@"player"];
        NSArray *highscore = [jsonObjects2 objectForKey:@"player_highscore"];
        
        /*for (NSDictionary *item in highscore)
         
        {*/
        self.textScore.text = @"Hello World!";
        //NSLog(@"Data has loaded successfully.");
    }
    

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
