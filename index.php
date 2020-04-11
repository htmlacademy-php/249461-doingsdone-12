<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// Подключение к БД
require_once('init.php');

// Функции с запросами на получение списка тасков
require_once('selecttasks.php');

// Для тестов выбор id пользователя
$user_profile = "1";

// Работа с БД

/*function show_tasks ($user_id) {
    $sql = 'SELECT tasks.id, status, task_name, run_to, project_id FROM tasks ' . 'JOIN projects ON tasks.project_id = projects.id ' . 'WHERE tasks.user_id = "' . $user_id . '"';

    return $sql;
}*/

if (!$db_connect) {
    $error = mysqli_connect_error();
    print("Ошибка Б=базы данных " . $error);
} else {
    //Показ имени пользователя
    $sql = 'SELECT user_name FROM users WHERE id = "' . $user_profile . '"';
    $result = mysqli_query($db_connect, $sql);
    if ($result) {
        $user_name = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($db_connect);
        print("Ошибка базы данных " . $error);
    }

    // Массив для постороения категорий
    $sql = 'SELECT id, project_name FROM projects WHERE user_id = "' . $user_profile . '"';
    $result = mysqli_query($db_connect, $sql);
    if ($result) {
        $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($db_connect);
        print("Ошибка базы данных " . $error);
    }

    // Вывод данных для подсчета задач по категориям
    $tasks_arr = mysqli_query($db_connect, show_tasks($user_profile));

    if ($tasks_arr) {
        $all_tasks = mysqli_fetch_all($tasks_arr, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($db_connect);
        print("Ошибка базы данных " . $error);
    }

    //сортировка задач по проектам
    if (isset($_GET['cat_id'])) {
        $cat_project = filter_input(INPUT_GET, 'cat_id', FILTER_SANITIZE_NUMBER_INT);
        $projects_id_arr = array_column($projects, 'id');
        $is_cat_id = in_array($cat_project, $projects_id_arr);
    }

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



// Подключение функций
require_once('functions.php');

// Файл с функцией подключения темплейтов
require_once('helpers.php');

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
