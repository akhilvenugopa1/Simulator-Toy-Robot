<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\SimulatorClass;

class StartRobot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start-robot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    public function __construct()
    {
        parent::__construct();
    }
    
    public function showSuccess($command){
         print($command . " is Successful ! \n \n");
    }
    
    public function showError($command, $msg){
         print("Error in `$command` " .$msg ."\n \n");
    }
    
    /**
     * Execute the console command.
     */
    public function handle(SimulatorClass $simulation)
    {
        print("Commands for Robot :: \n 1. PLACE X,Y,F \n 2. MOVE \n 3. LEFT \n 4. RIGHT \n 5. REPORT \n 6. EXIT".
              "\n\n NOTE:: Use the following values for each parameter \n X = 0,1,2,3 or 4 \n Y = 0,1,2,3 or 4 \n F = NORTH, SOUTH, EAST or WEST \n ");
        $command = " ";
        while($command != "EXIT"){
            $command = $this->ask('Enter your command?');
            /* Take the Place Command*/
            if (preg_match('/^PLACE /', $command)) {
                if($values = $this->checkPlaceCommand($command)){
                    if($check = $simulation->place((int)$values[0], (int)$values[1], $values[2])){
                        $this->showSuccess($command);
                    }else{
                        $msg = $simulation->returnError();
                        $this->showError($command, $msg);
                    }
                }else{
                    $this->showError($command, "Place Command is not valid. Check the commands rule mentioned !");
                }
            }elseif ($command == 'REPORT') {
                $report = $simulation->report();
                print($report."\n\n");
            }elseif ($command == 'MOVE') {
                $return = $simulation->move();
                if($return){
                    $this->showSuccess($command);
                }else{
                    $msg = $simulation->returnError();
                    $this->showError($command, $msg);
                }
            }elseif ($command == 'LEFT') {
                $return = $simulation->left();
                if($return){
                    $this->showSuccess($command);
                }else{
                    $msg = $simulation->returnError();
                    $this->showError($command, $msg);
                }
            }elseif ($command == 'RIGHT') {
                $return = $simulation->right();
                if($return){
                    $this->showSuccess($command);
                }else{
                    $msg = $simulation->returnError();
                    $this->showError($command, $msg);
                }
            }elseif($command != 'EXIT'){
                $this->showError($command, "Command is not valid. Check the commands rule mentioned !");
            }
        }
    }
    
    public function checkPlaceCommand($command){
        $temp_command = explode(" ",$command);
        $params = explode(",",$temp_command[1]);
        if($temp_command[0] == "PLACE" && count($params) == 3 ) {
            //  ($temp_x > 0 && $temp_x <= 4  && $temp_y > 0 && $temp_y <= 4)
            if(in_array($params[0], [0,1,2,3,4]) && in_array($params[1], [0,1,2,3,4]) && in_array($params[2], ['NORTH','SOUTH','EAST','WEST'])){
                return [$params[0], $params[1], $params[2]];
            }
            return false;
        }
        return false;
    }
}
