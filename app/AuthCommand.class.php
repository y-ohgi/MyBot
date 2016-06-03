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
            $this->addErrorInResult("そんなコマンドは無い");
            return;
        }
    }

    // bot auth signup 'username' 'password'
    private function signup($message){
        $str = explode(" ", $message);
        if(count($str) !== 5){
            $this->addErrorInResult("ちゃんとしたコマンドが欲しい");
            return;
        }

        $username = $str[3];
        $password = $str[4];
        $token = uniqid();
        $user = $this->getUser($username);
        
        if($user){
            $this->addErrorInResult("既に存在するユーザー");
            return;
        }

        try{
            $sql = 'INSERT INTO users(username, password, token) values(:username, :password, :token)';
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':password', password_hash($username. $password, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->bindValue(':token', $token, PDO::PARAM_STR);
            $stmt->execute();

            $this->addTokenInResult($token);
            $this->addResult("登録完了");
        }catch(Exception $e){
            $this->addErrorInResult("error");
            return;
        }

    }

    private function signin($message){
        $str = explode(" ", $message);
        if(count($str) !== 5){
            $this->addErrorInResult("ちゃんとしたコマンドを打って下さい");
            return;
        }

        $username = $str[3];
        $password = $str[4];
        $user = $this->getUser($username);
        $passhash = $username. $password;
        var_dump($user);

        if(!$user || !password_verify($passhash, $user['password'])){
            $this->addErrorInResult("usernameかpssswordが間違ってる");
            return;
        }else{
            $token = $this->updateToken($username);
            $this->addTokenInResult($token);
            $this->addResult("ログインできた");
            return;
        }
    }

    // tokenを抹消後 ユーザー側でうんぬん
    private function signout(){
        
    }
}