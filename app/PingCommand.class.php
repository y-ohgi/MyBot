<?php

namespace Sprint;

class PingCommand extends Command {
    private $result = "";
    
    public function excute($message){
        $this->result = "pong";

    }

    public function getResult(){
        return $this->result;
    }
}