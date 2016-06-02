<?php

namespace Sprint;

class PingCommand extends Command {
    public function execute($message){
        $this->addResult("pong");
    }
}