<?php

namespace Sprint;

use \PDO;
use \Exception;

class TalkCommand extends Command {
    use Auth;
    
    public function execute($message){
        $bot = new Bot($this->user_id);

        if(!$this->isAuth()){
            $this->addErrorInResult(401);
            $word = $bot->getWord(401);
            $this->addWordInResult($word);
            return;
        }
        
        $word = $bot->getWord();
        $favper = $bot->addFavorability();
        
        
        $this->addWordInResult($word);
    }
}
