<?php

namespace Sprint;

class WeatherCommand extends Command {
    use Auth;
    
    public function excute($message){
        $str = explode(" ", $message);
        
        if(count($str) === 2){
            $this->result = "要認証";
            return;
        }else if($this->isAuth($str[2]) === false){
            $this->result = "要認証";
            return;
        }

        $this->result = "OK";
    }

    
    
}
