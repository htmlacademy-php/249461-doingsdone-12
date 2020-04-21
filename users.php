<?php
    // Для тестов выбор id пользователя
    $user_profile = "1";

    //Показ имени пользователя
    $sql = 'SELECT user_name FROM users WHERE id = "' . $user_profile . '"';
    $result = mysqli_query($db_connect, $sql);
    if ($result) {
        $user_name = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($db_connect);
        print("Ошибка базы данных " . $error);
    }
?>
