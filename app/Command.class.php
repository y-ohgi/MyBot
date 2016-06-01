<?php

namespace Sprint;

abstract class Command{
    abstract public function excute($message);
    abstract public function getResult();
}
