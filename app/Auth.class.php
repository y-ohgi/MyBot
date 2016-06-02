<?php

namespace Sprint;

use \PDO;
use \Exception;

trait Auth{
    
    public function getUser($username){
        try{
            $sql = 'SELECT * FROM users WHERE username = :username';
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
        }catch(Exception $e){
            $this->result = "error";
            return false;
        }
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getUserByToken(){
        try{
            $sql = 'SELECT * FROM users WHERE token = :token';
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':token', $this->token, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            $this->addErrorInResult("tokenからうけとれなかった");
            return false;
        }
    }
    
    protected function isAuth(){
        $user = $this->getUserByToken();
        if($user === false || $user['token'] !== $this->token){
            return false;
        }
        // TODO: usernameとかを 変数へ格納
        return true;
    }

    
    protected function isOwner($username){
        try{
            $sql = 'SELECT * FROM users WHERE username = :username';
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
        }catch(Exception $e){
            $this->result = "error";
            return false;
        }
        // TODO: オーナーかの判定
        
        return ;
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

