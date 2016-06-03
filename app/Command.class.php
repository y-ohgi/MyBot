<?php

namespace Sprint;

use \PDO;
use \Exception;

// XXX: 一通りaddじゃなくてsetだよなぁ、と
abstract class Command{
    protected $result = array();
    protected $token = array();
    
    abstract public function execute($message);
    
    public function getResult(){
        // SELECT body FROM bot_type_words WHERE (id = $this->result[error] || $this->result['token']) && bot_id = BOT::getType();
        $this->addResult(array('type'=>$this->getTypeName()));
        return $this->result;
    }

    // bot に入れるべきなのはわかっている
    public function getTypeName(){
        $sql = "SELECT typename FROM bot_type_master WHERE id = (SELECT type_id FROM bot ORDER BY id DESC LIMIT 1)";
        $stmt = Dbh::get()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    // data or 特別なkeyを入れる用
    public function addResult($res){
        if(empty($res)){
            return;
        }else if(is_array($res)){
            $this->result[key($res)] = $res[key($res)];
        }else{
            $this->result["data"] = $res;
        }
    }

    // 認証用token用
    public function addTokenInResult($res){
        if(empty($res)){
            return;
        }else{
            $this->result["token"] = $res;
        }
    }

    // botのセリフ用
    public function addWordInResult($res){
        if(empty($res)){
            return;
        }else{
            $this->result["word"] = $res;
        }
    }
    
    // error用
    public function addErrorInResult($res, $botflg = false){
        if(empty($res)){
            return;
        }else if(is_numeric($res) && $botflg === true){
            try{
                $sql = "SELECT body FROM bot_word_master WHERE type_id = (SELECT type_id FROM bot ORDER BY id DESC LIMIT 1) AND bot_state_id = :bot_state_id";
                $stmt = Dbh::get()->prepare($sql);
                $stmt->bindValue(':bot_state_id', intval($res), PDO::PARAM_INT);
                $stmt->execute();

                $word = $stmt->fetchColumn();
                
                $this->addWordInResult($word);
                $this->result["error"] = $res;
            }catch(Exception $e){
                var_dump($e->getMessage);
            }
        }else{
            $this->result["error"] = $res;
        }
    }

    // XXX: ここで token を設定するのどうにかしたい => 認証に関係あるところに入れたい
    public function setToken($token){
        $this->token = $token;
    }
}
