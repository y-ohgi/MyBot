<?php

namespace Sprint;

use \PDO;
use \Exception;

class Bot {
    private $user_id;
    private $bot;

    public function __construct($userid){
        $this->user_id = $userid;
        $this->getBot();
    }

    public function getBot(){
        $sql = "SELECT * FROM bot_state ORDER BY id DESC LIMIT 1";
        $stmt = Dbh::get()->prepare($sql);
        $stmt->execute();
        $botrow = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($botrow)){
            throw new Exception('botが居ないサーバー');
        }else{
            $this->bot = $botrow;
        }
        
        return $botrow;
            
        
    }

    // 現在の好感度, 現在のオーナー, botの属性
    public function getState(){
        $res = array();

        $res['favorability'] = $this->getFavorability();
        
        return $res;
    }
    
    // bot の好感度を取得
    public function getFavorability(){
        $bot_id = $this->bot['id'];
        $bot_favo_id = 0;

        try{
            $sql = "SELECT bot_id, percent FROM bot_favorabilites WHERE user_id = :user_id ORDER BY id DESC LIMIT 1";
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->execute();
            $favorow = $stmt->fetch(PDO::FETCH_ASSOC);
            if(empty($favorow)){
                $bot_favo_id = 0;
            }else{
                $bot_favo_id = $favorow['bot_id'];
            }

            // 無ければ作る
            if($bot_favo_id === 0 || $bot_id !== $bot_favo_id){
                $sql = "INSERT INTO bot_favorabilites(bot_id, user_id) VALUES(:bot_id, :user_id)";

                $stmt = Dbh::get()->prepare($sql);
                $stmt->bindValue(':bot_id', $bot_id, PDO::PARAM_INT);
                $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
                $stmt->execute();
            
                return 0;
            }else{
                return intval($favorow['percent']);
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
        
    }

    // 好感度を上げる
    public function addFavorability($num){
        $favper = $this->getFavorability();

        $favper += intval($num);

        try{
            $sql = "UPDATE bot_favorabilites SET percent = :percent WHERE user_id = :user_id AND bot_id = :bot_id";
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':percent', $favper, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindValue(':bot_id', $this->bot['id'], PDO::PARAM_INT);
            $stmt->execute();
        }catch(Exception $e){
            return $e->getMessage();
        }

        return $favper;
    }

    
    

    
    
}
