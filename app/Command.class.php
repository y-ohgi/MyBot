<?php

namespace Sprint;

abstract class Command{
    protected $result = array();
    protected $token = array();
    
    abstract public function execute($message);
    
    public function getResult(){
        // SELECT body FROM bot_type_words WHERE (id = $this->result[error] || $this->result['token']) && bot_id = BOT::getType();
        return $this->result;
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
    public function addErrorInResult($res/* , $botflg = false */){
        if(empty($res)){
            return;
        }else{
            $this->result["error"] = $res;
        }

        /* if($botflg){ */
        /*     if(!is_int($res))$res = 400; */
        /*     $this->addWordInResult($res); */
        /* } */
    }

    // XXX: ここで token を設定するのどうにかしたい => 認証に関係あるところに入れたい
    public function setToken($token){
        $this->token = $token;
    }
}
