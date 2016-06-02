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
        }

        $res = array();
        $image = $str[2];
        $labels = API::GCV($image);

        // 現在の属性 の好みかどうか
        foreach($labels as $label){
            array_push($res, $label['description']);
        }
        $res = implode(',', $res);

        $bot->addFavorability(20);

        $this->addResult($res);
        $this->addResult(array('bot' => $bot->getState()));
    }
    
    
}
