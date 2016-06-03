<?php

namespace Sprint;

use \Exception;
use \PDO;

class AuthCommand extends Command {
    use Auth;

    public function execute($message){
        $str = explode(" ", $message);

        if($str[2] === "signup"){
            $this->signup($message);
            return;
        }else if($str[2] === "signin"){
            $this->signin($message);
            return;
        }else if($str[2] === "signout"){
            $this->signout();
            return;
        }else{
            $this->addErrorInResult(404, true);
            return;
        }
    }

    // bot auth signup 'username' 'password'
    private function signup($message){
        $str = explode(" ", $message);
        if(count($str) !== 5){
            $this->addErrorInResult(400, true);
            return;
        }

        $username = $str[3];
        $password = $str[4];
        $token = uniqid();
        $user = $this->getUser($username);
        
        if($user){
            $this->addErrorInResult(409, true);
            return;
        }

        try{
            $sql = 'INSERT INTO users(username, password, token) values(:username, :password, :token)';
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':password', password_hash($username. $password, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->bindValue(':token', $token, PDO::PARAM_STR);
            $stmt->execute();

            $user_id = Dbh::get()->lastInsertId('id');

            $bot = new Bot($user_id);
            $word = $bot->getWord(201, 'ユーザー');

            $this->addTokenInResult($token);
            $this->addWordInResult($word);
        }catch(Exception $e){
            $this->addErrorInResult(500, true);
            return;
        }

    }

    private function signin($message){
        $str = explode(" ", $message);
        if(count($str) !== 5){
            $this->addErrorInResult(400, true);
            return;
        }

        $username = $str[3];
        $password = $str[4];
        $user = $this->getUser($username);
        $user_id = $user['id'];
        $passhash = $username. $password;

        if(!$user || !password_verify($passhash, $user['password'])){
            $this->addErrorInResult(400, true);
            return;
        }else{
            $token = $this->updateToken($username);
            
            $bot = new Bot($user_id);
            $word = $bot->getWord(200);
            
            $this->addTokenInResult($token);
            $this->addWordInResult($word);
            return;
        }
    }

    // tokenを抹消後 ユーザー側でうんぬん
    private function signout(){
        
    }
}