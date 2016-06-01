<?php

namespace Sprint;

abstract class Command{
    protected $result;
    abstract public function excute($message);
    public function getResult(){
        return $this->result;
    }
}
