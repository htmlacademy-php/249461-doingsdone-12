<?php
// Подключение функций
require_once('functions.php');

// Файл с функцией подключения темплейтов
require_once('helpers.php');

// Подключение к БД
require_once('init.php');

// Функции с запросами на получение списка тасков
require_once('select-tasks.php');

if (!$db_connect) {
    $error = mysqli_connect_error();
    print("Ошибка Базы данных " . $error);
} else {
    require_once('users.php');

    require_once('projects-list.php');
}

// Подключение темплейтов
$projects_list = include_template ('projects.php', [
    'all_tasks' => $all_tasks,
    'projects' => $projects
]);

$main_content = include_template ('add-task.php', [
    'projects_list' => $projects_list,
    'all_tasks' => $all_tasks,
    'projects' => $projects
]);

$layout_content = include_template('layout.php', [
    'content' => $main_content,
    'title' => 'Добавление задачи',
    'user_name' => $user_name
]);

// Вывод темплейтов
print($layout_content);
?>
