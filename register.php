<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;
    $errors = [];

    $req_fields = ['email', 'password', 'user_name'];

    foreach ($req_fields as $key => $field) {
        if (empty($form[$field])) {
            $errors[$field] = "Поле не заполнено";
        }
    }

    if (!empty($form['email'])) {
        if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Проверьте правильность введенного email';
        }
    }

    if (empty($errors)) {
        $email = mysqli_real_escape_string($db_connect, $form['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($db_connect, $sql);

        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользовательс таким email уже сущетвует';
        } else {
            $password = password_hash($form['password'], PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (reg_date, email, user_name, password) VALUES (NOW(), ?, ?, ?)';
            $stmt = db_get_prepare_stmt($db_connect, $sql, [$form['email'], $form['user_name'], $password]);
            $res = mysqli_stmt_execute($stmt);
        }

        if ($res && empty($errors)) {
            header("Location: /auth.php");
        }
    }
}


$main_content = include_template('new-user.php', [
    'form' => $form,
    'errors' => $errors

]);

$layout_content = include_template('layout.php', [
    'content' => $main_content,
    'title' => 'Регистрация нового пользователя',
    'user_name' => $user_name
]);

print($layout_content);
?>
