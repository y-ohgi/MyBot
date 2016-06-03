<?php

namespace Sprint;

use \PDO;
use \Exception;

trait Auth{
    private $user_id;
    private $user;
    
    
    public function getUser($username){
        // TODO: ここと getUserByToken() の共通user取得処理をシングルトンにする
        try{
            $sql = 'SELECT * FROM users WHERE username = :username';
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->user = $user;
            $this->user_id = $user['id'];
        }catch(Exception $e){
            $this->result = "error";
            return false;
        }
        
        return $user;
    }
    
    public function getUserByToken(){
        try{
            $sql = 'SELECT * FROM users WHERE token = :token';
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':token', $this->token, PDO::PARAM_STR);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->user = $user;
            $this->user_id = $user['id'];
        }catch(Exception $e){
            $this->addErrorInResult("tokenからうけとれなかった");
            return false;
        }
        
        return $user;
    }
    
    protected function isAuth(){
        $user = $this->getUserByToken();
        if($user === false || $user['token'] !== $this->token){
            return false;
        }
        // TODO: usernameとかを 変数へ格納
        return true;
    }

    // botに置くのが正解？
    protected function isOwner(){
        if(!$this->isAuth()){
            return false;
        }
        
        $user = $this->getUserByToken();
        
        $sql = "SELECT * FROM bot ORDER BY id DESC LIMIT 1";
        $stmt = Dbh::get()->prepare($sql);
        $stmt->execute();
        $botrow = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user['id'] === $botrow['user_id']){
            return true;
        }

        return false;
    }

    protected function updateToken($username){
        $newtoken = uniqid();

        try{
            $sql = 'UPDATE users SET token = :token WHERE username = :username;';
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':token', $newtoken, PDO::PARAM_STR);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
        }catch(Exception $e){
            $this->addErrorInResult("ログイン情報のアップデートに失敗");
            return false;
        }
        return $newtoken;
    }
}

