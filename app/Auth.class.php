<?php

namespace Sprint;

use \PDO;


trait Auth{
    
    public function getUser($username){
        $sql = 'SELECT * FROM users WHERE username = :username';
        $stmt = Dbh::get()->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    protected function isAuth($username){
        $user = $this->getUser($username);
        if($user === false){
            return false;
        }
        
        return true;
    }
    
    protected function isOwner($username){
        $sql = 'SELECT * FROM users WHERE username = :username';
        $stmt = Dbh::get()->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        return ;
    }
}

