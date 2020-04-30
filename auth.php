<?php
// Подключение к БД
require_once('init.php');

// Подключение функций
require_once('functions.php');

// Файл с функцией подключения темплейтов
require_once('helpers.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $required = ['email', 'password'];
    $errors = [];

    //Проверка на пустые поля
    foreach ($required as $key => $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Это поле должно быть заполнено';
        }
    }

    //Поиск пользователя по введеному email
    $email = mysqli_real_escape_string($db_connect, $form['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($db_connect, $sql);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    //Проверка email & password
    if (!count($errors) and $user) {
        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Введен неверный пароль';
        }
    } else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $page_content = include_template('auth.php', [
            'form' => $form,
            'errors' => $errors
        ]);
    } else {
        header("Location: index.php");
        exit();
    }

} else {
    $page_content = include_template('auth.php', []);

    if (isset($_SESSION['user'])) {
        header("Location: /index.php");
        exit();
    }
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Войти'
]);

print ($layout_content);

?>
