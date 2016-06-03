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
        $sql = "SELECT * FROM bot ORDER BY id DESC LIMIT 1";
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
        
        $res['type_id'] = $this->bot['type_id'];
        $res['typename'] = $this->getTypeName($this->bot['type_id']);
        $res['bot_state_id'] = $this->getFavoRankId();
        $res['favorability'] = $this->getFavorability();
        
        return $res;
    }

    public function getTypeName($type_id){
        $sql = "SELECT typename FROM bot_type_master WHERE id = :type_id";
        $stmt = Dbh::get()->prepare($sql);
        $stmt->bindValue(':type_id', $type_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }
    
    // bot の好感度を取得
    public function getFavorability(){
        $bot_id = $this->bot['id'];
        $bot_favo_id = 0;
        echo "user_id: ";
        var_dump($this->user_id);

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

    public function getFavoRankId($favper = 0){
        $favper = $this->getFavorability();

        if($favper <= FAVO_RANK_ZERO){
            return WORD_FAVO_ZERO;
        }else if($favper <= FAVO_RANK_LOW){
            return WORD_FAVO_LOW;
        }else if($favper <= FAVO_RANK_MIDDLE){
            return WORD_FAVO_MIDDLE;
        }else if($favper <= FAVO_RANK_HIGHT){
            return WORD_FAVO_HIGHT;
        }else if($favper >= FAVO_RANK_OWNER){
            return WORD_FAVO_OWNER;
        }
    }

    // 好感度を上げる
    // 何も指定されなければ5%アップ
    public function addFavorability($num = 5){
        $favper = $this->getFavorability();

        $favper += intval($num);

        try{
            $sql = "UPDATE bot_favorabilites SET percent = :percent, updated_at = :updated_at WHERE user_id = :user_id AND bot_id = :bot_id";
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':percent', $favper, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindValue(':bot_id', $this->bot['id'], PDO::PARAM_INT);
            $stmt->bindValue(':updated_at', date('Y-m-d h:m:s'), PDO::PARAM_STR);
            $stmt->execute();

            $sql = "INSERT INTO bot_present_logs(bot_id, user_id, added_percent) VALUES(:bot_id, :user_id, :percent)";
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':bot_id', $this->bot['id'], PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindValue(':percent', $num, PDO::PARAM_INT);
            $stmt->execute();

        }catch(Exception $e){
            return $e->getMessage();
        }

        // TODO: 100% 超えたよメッセージ
        // 好感度が100% 以上ならシーズンを更新
        if($favper >= 100){
            $s = $this->updateSeason();
            if($s === true){
                echo "changed season!!";
            }else{
                echo $s;
            }
        }

        return $favper;
    }

    
    
    public function updateSeason($owner_id = ""){
        $owner_id = $owner_id? $owner_id:$this->user_id;

        try{
            $sql = "INSERT INTO bot(type_id, user_id) VALUES(:type_id, :user_id)";
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':type_id', intval($this->bot['type_id']), PDO::PARAM_INT);
            $stmt->bindValue(':user_id', intval($this->user_id), PDO::PARAM_INT);
            $stmt->execute();
        }catch(Exception $e){
            return $e->getMessage();
        }

        return true;
        
    }

    // botのセリフの取得
    // 引数は bot_state_master id
    public function getWord($stateid = null, $word = ""){
        $state_id = $stateid? $stateid : $this->getFavoRankId();
        $word = $word? $word : ""; // "マスター";

        if($stateid === null){
            $sql = "SELECT body FROM bot_word_master WHERE type_id = :type_id AND (bot_state_id = :bot_state_id OR bot_state_id = 1 OR bot_state_id = 20 OR bot_state_id = 21) ORDER BY RAND();";
        }else{
            $sql = "SELECT body FROM bot_word_master WHERE type_id = :type_id AND bot_state_id = :bot_state_id ORDER BY RAND() LIMIT 1";
        }

        $stmt = Dbh::get()->prepare($sql);
        $stmt->bindValue(':type_id', $this->bot['type_id'], PDO::PARAM_INT);
        $stmt->bindValue(':bot_state_id', $state_id, PDO::PARAM_INT);
        $stmt->execute();
        $body = $stmt->fetchColumn();


        $body = str_replace('#{$word}', $word, $body);
        return $body;
    }
}
