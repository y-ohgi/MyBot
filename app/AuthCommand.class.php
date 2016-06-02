<?php

namespace Sprint;

class Auth extends Command {

    public function excute($message){
        $str = explode(" ", $message);

        if($str[2] === "signup"){
            $this->signup($message);
            return;
        }else if($str[2] === "signin"){
            $this->signin($message);
            return;
        }else if($str[2] === "signou"){
            $this->signout();
            return;
        }else{
            $this->result = "そんなコマンドは無い";
            return;
        }
    }

    private function signup($message){
        $str = explode(" ", $message);
        
        
    }

    private function signin($message){
        $str = explode(" ", $message);

    }
    
    private function signout(){

    }
}