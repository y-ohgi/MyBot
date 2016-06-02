<?php

namespace Sprint;

class WeatherCommand extends Command {
    use Auth;
    
    public function execute($message){
        $str = explode(" ", $message);

        if($this->isAuth()){
            $user = $this->getUserByToken();
            $user_id = $user['id'];
            $bot = new Bot($user_id);

            $favper = $bot->getFavorability();
            
            $this->addResult("晴れじゃね");
        }else{
            $this->addErrorInResult("要認証");
        }
    }
    
    
}
