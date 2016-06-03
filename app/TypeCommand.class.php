<?php

namespace Sprint;

use \PDO;
use \Exception;

class TypeCommand extends Command{
    use Auth;

    public function execute($message){
        // bot type list
        // bot type change "属性"
        $str = explode(" ", $message);
        
        if(!array_key_exists(2, $str)){
            $this->addErrorInResult(400);
            $word = $bot->getWord(400);
            $this->addWordInResult($word);
            
            return;
        }

        if($this->isOwner()){
            $user = $this->getUserByToken();
            $user_id = $user['id'];
            $bot = new Bot($user_id);
        }else{
            $user = $this->getUserByToken();
            $user_id = $user['id'];
            $bot = new Bot($user_id);

            $this->addErrorInResult(401);
            $word = $bot->getWord(401);
            $this->addWordInResult($word);
            return;
        }

        if($str[2] === "list"){
            $types = $this->getList(true);
            var_dump($types);
            $this->addWordInResult($types);
            return;
        }else if($str[2] === "change" && array_key_exists(3, $str)){
            if($this->changeType($str[3])){
                $word = $bot->getWord(WORD_SUCCESS);
                $this->addWordInResult($word);
            }else{
                $this->addErrorInResult(400);
                $word = $bot->getWord(400);
                $this->addWordInResult($word);
            }
        }else{
            $this->addErrorInResult(400);
            $word = $bot->getWord(400);
            $this->addWordInResult($word);
        }

    }

    public function getList($flg = false){
        try{
            $sql = "SELECT typename FROM bot_type_master";
            $stmt = Dbh::get()->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($flg === true){
                foreach($rows as $row){
                    $res[] = "・". $row['typename'];
                }
                $res = implode("\n", $res);
            }else{
                $res = $rows;
            }

            return $res;
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }
    }

    public function changeType($typename){
        echo $typename;
        try{
            $sql = "SELECT id FROM bot ORDER BY id DESC LIMIT 1";
            $stmt = Dbh::get()->prepare($sql);
            $stmt->execute();
            $bot_id = $stmt->fetchColumn();
            
            $sql = "UPDATE bot SET type_id = (SELECT id FROM bot_type_master WHERE typename = :typename) WHERE id = :bot_id";
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':typename', $typename, PDO::PARAM_STR);
            $stmt->bindValue(':bot_id', $bot_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return true;
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }
    }
        
}

