<?php

namespace Sprint;

use \PDO;
use \Exception;

class PresentCommand extends Command{
    use Auth;

    public function execute($message){
        $str = explode(" ", $message);
        
        if(!array_key_exists(2, $str)){
            return;
        }
        if($this->isAuth()){
            $user = $this->getUserByToken();
            $user_id = $user['id'];
            $bot = new Bot($user_id);
        }else{
            $this->addErrorInResult('用認証');
            return false;
        }

        $res = "";
        $word = "";
        $percent = 0;
        $percentdefault = 20;
        $boundlabel = array();
        $image = $str[2];
        $labels = API::GCV($image);

        $sql = "SELECT percent FROM present_match_master WHERE label IN (:lab0, :lab1, :lab2) LIMIT 1";
        $stmt = Dbh::get()->prepare($sql);
        $num = 0;
        foreach($labels as $label){
            $stmt->bindValue(':lab'.$num, $label['description'], PDO::PARAM_STR);
            array_push($boundlabel, $label['description']);
            $num++;
        }
        $stmt->execute();
        $percent = (int)$stmt->fetchColumn();
        
        $res = implode(',', $boundlabel);

        if($percent > 0){
            $word = $bot->getWord(WORD_THANKS_V2);
        }else if($percent < 0){
            $word = $bot->getWord(WORD_SORRY);
        }else{
            $percent = $percentdefault;
            $word = $bot->getWord(WORD_THANKS);
        }
        
        $bot->addFavorability($percent);

        $this->addWordInResult($word);
        $this->addResult(array('favo' => $percent));
        $this->addResult($res);
    }
        
}
