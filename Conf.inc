<?php

class Config{
    private static $data = array (
        'dsn'=> 'mysql:dbname=mybot; host=localhost;unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock; charset=utf8;',
        'user'=> 'root',
        'password'=> 'root'
    );

    public static function get($k){
        return self::$data[$k];
    }
}

