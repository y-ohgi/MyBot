<?php

namespace Sprint;

class PingCommand extends Command {
    public function excute($message){
        $this->addResult("pong");
    }
}