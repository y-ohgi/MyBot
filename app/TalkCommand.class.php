<?php

namespace Sprint;

use \PDO;
use \Exception;

class TalkCommand extends Command {
    use Auth;
    
    public function execute($message){
        if(!$this->isAuth()){
            $this->addErrorInResult(401);
            $word = $bot->getWord(401);
            $this->addWordInResult($word);
            return;
        }

        $bot = new Bot($this->user_id);

        if($this->isOwner()){
            $word = $bot->getWord(WORD_FAVO_OWNER, $this->user['username']);
        }else{
            $word = $bot->getWord();
            $favper = $bot->addFavorability(5);
        }
        
        
        $this->addWordInResult($word);
    }
}
