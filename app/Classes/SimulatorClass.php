<?php
namespace App\Classes;
 
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class SimulatorClass
{
    protected $current_x_axis;
    protected $current_y_axis;
    protected $current_front;
    protected $onBoard = false;

    protected $error;
    
    /**  Place Function.     **/
    public function place(int $x, int $y, string $f)
    {
        if ($this->isOnBoard()) {
            $this->error = 'Robot is already been placed on the table.';
            return false;
        }
        $this->current_x_axis = $x;
        $this->current_y_axis = $y;
        $this->current_front = $f;
        $this->onBoard = true;
        return true;
    }
    
    /**    Move Function.      **/
    public function move()
    {
        if (!$this->isOnBoard()) {
            $this->error = 'Robot is not been placed on the table.';
            return false;
        }
        $temp_x = $this->current_x_axis;
        $temp_y = $this->current_y_axis;

        switch ($this->current_front) {
            case 'NORTH':
                $temp_y++;
                break;
            case 'SOUTH':
                $temp_y--;
                break;
            case 'EAST':
                $temp_x++;
                break;
            case 'WEST':
                $temp_x--;
                break;
        }
        
        //check if the command will make the rorbot fall /  
        //  $temp_x > 0 && $temp_x <= 4  && $temp_y > 0 && $temp_y <= 4 
        if(in_array($temp_x, [0,1,2,3,4]) && in_array($temp_y, [0,1,2,3,4])){
            $this->current_x_axis = $temp_x;
            $this->current_y_axis = $temp_y;
            return true;
        }else{
            $this->error = 'The robot will fall from TableTop . Ingnoring the Command';
            return false;
        }
    }
    
    /**    Left Function.    **/
    public function left()
    {
        if (!$this->isOnBoard()) {
            $this->error = 'Robot is not been placed on the table.';
            return false;
        }
        switch ($this->current_front) {
            case 'NORTH':
                $this->current_front = "WEST";
                break;
            case 'SOUTH':
                $this->current_front = "EAST";
                break;
            case 'EAST':
                $this->current_front = "NORTH";
                break;
            case 'WEST':
                $this->current_front = "SOUTH";
                break;
        }
        return true;
    }
    
    /**    Right Function.    **/
    public function right()
    {
        if (!$this->isOnBoard()) {
            $this->error = 'Robot is not been placed on the table.';
            return false;
        }
        switch ($this->current_front) {
            case 'NORTH':
                $this->current_front = "EAST";
                break;
            case 'SOUTH':
                $this->current_front = "WEST";
                break;
            case 'EAST':
                $this->current_front = "SOUTH";
                break;
            case 'WEST':
                $this->current_front = "NORTH";
                break;
        }
        return true;
    }
    
    /**     Get report.     **/
    public function report()
    {
        return $this->current_x_axis .','. $this->current_y_axis .','. $this->current_front;
    }
    
    /**     Get Error.     **/
    public function returnError()
    {
        return $this->error;
    }
    
    /**     Return robot status on the table.     **/
    protected function isOnBoard()
    {
        return $this->onBoard;
    }
}