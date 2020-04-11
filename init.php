<?php
    $db = [
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'database' => 'things_are_okay'
    ];

    $db_connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
    mysqli_set_charset($db_connect, 'utf8');
?>
