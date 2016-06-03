<?php

namespace Sprint;

use \PDO;

class StatusCommand extends Command {
    use Auth;
    
    public function execute($message){
        if(!$this->isAuth()){
            $this->addErrorInResult(401);
            $word = $bot->getWord(401);
            $this->addWordInResult($word);
            return;
        }

        
        $bot = new Bot($this->user_id);
        $state = $bot->getState();
        
        if($this->isOwner()){
            var_dump($state);
            $st = <<< TEXT
* 現在の属性:
　- {$state['typename']}
* 現在のマスター:
　- あなた

* tips:
　- マスターは bot type 'typename' で属性を変えることができるよ！
　- 属性一覧は bot type list で確認できるよ！
TEXT;
                
            $this->addWordInResult($st);
            return;
        }else if($this->isAuth()){
            $st = <<< TEXT
* 現在の属性:
　- {$state['typename']}
* 現在の好感度:
　- {$state['favorability']}
TEXT;
                
            $this->addWordInResult($st);
            return;
        }
    }
}
