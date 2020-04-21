<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

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

    // Данные для показа тасков по всем категориям или одной выбранной
    if ($cat_project) {
        $sql = show_cat_tasks($user_profile, $cat_project);
    } else {
        $sql = show_tasks($user_profile);
    }
    $tasks_arr = mysqli_query($db_connect, $sql);

    if ($tasks_arr) {
        $tasks = mysqli_fetch_all($tasks_arr, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($db_connect);
        print("Ошибка базы данных " . $error);
    }
}


// Подключение темплейтов
$main_content = include_template ('main.php', [
    'tasks' => $tasks,
    'all_tasks' => $all_tasks,
    'projects' => $projects,
    'show_complete_tasks' => $show_complete_tasks,
    'is_cat_id' => $is_cat_id
]);

$layout_content = include_template('layout.php', [
    'content' => $main_content,
    'title' => 'Дела в порядке',
    'user_name' => $user_name
]);

// Вывод темплейтов
print($layout_content);
?>
