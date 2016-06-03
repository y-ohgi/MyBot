<?php

namespace Sprint;

use \PDO;

// ブロントさんクラス
class BurontCommand extends Command {
    use Auth;
    
    public function execute($message){
        $bot = new Bot($this->user_id);

        if($this->isOwner()){
            $word = $this->getMaxim();
            
            $this->addWordInResult($word);
        }else{
            $errorcode = 401;
            $word = $bot->getWord($errorcode);
            $this->addErrorInResult($errorcode);
            $this->addWordInResult($word);
            return;
        }
    }

    // ブロントさんの名言を返す
    public function getMaxim(){
        $sql = "SELECT body FROM buront_maxims ORDER BY RAND() LIMIT 1";
        $stmt = Dbh::get()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
