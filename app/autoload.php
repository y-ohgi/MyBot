<?php

require __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function ($class) {
    static $classes = [];
    if (empty($classes)) {
        $classes = [
            'sprint\\bot' => '/Bot.class.php',
            'sprint\\dbh' => '/Dbh.php',
            'sprint\\api' => '/Api.class.php',
            'sprint\\auth' => '/Auth.class.php',
            
            'sprint\\command' => '/Command.class.php',
            'sprint\\pingcommand' => '/PingCommand.class.php',
            'sprint\\todocommand' => '/TodoCommand.class.php',
            'sprint\\authcommand' => '/AuthCommand.class.php',
            'sprint\\talkcommand' => '/TalkCommand.class.php',
            'sprint\\weathercommand' => '/WeatherCommand.class.php',
            'sprint\\presentcommand' => '/PresentCommand.class.php',
        ];
        
    }
    $cn = strtolower($class);
    if (isset($classes[$cn])) {
        require(__DIR__ . $classes[$cn]);
    }
}, true, false);
