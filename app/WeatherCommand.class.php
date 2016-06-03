<?php

namespace Sprint;

class WeatherCommand extends Command {
    use Auth;
    
    public function execute($message){
        $str = explode(" ", $message);

        if($this->isOwner()){
            $this->addResult("晴れじゃね");
        }else{
            $this->addErrorInResult("要認証");
        }
    }
    
    
}
