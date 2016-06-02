<?php

namespace Sprint;

class TalkCommand extends Command {
    use Auth;
    
    public function execute($message){
        $str = explode(" ", $message);

        if(!$this->isAuth()){
            $this->addErrorInResult("要認証");
        }

        $this->addResult("はわわー");

        
        // TODO: 現在の好感度を持ってくる
        // ランダムでセリフを持ってくる
        // SELECT body FROM type_words WHERE 
    }
}
