<?php

namespace Sprint;

class WeatherCommand extends Command {
    use Auth;
    
    public function excute($message){
        $str = explode(" ", $message);

        if($this->isAuth()){
            $this->addResult("晴れじゃね");
        }else{
            var_dump($message);
            var_dump($this->token);

            $this->addErrorInResult("要認証");
        }
    }
    
    
}
