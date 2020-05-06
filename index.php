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

    $search = $_GET['search'] ?? '';

    if ($search) {
        $sql = 'SELECT tasks.id, status, task_name, run_to, project_id, file_name, file_path FROM tasks ' . 'WHERE tasks.user_id = "' . $user_profile . '" AND MATCH(task_name) AGAINST(?)';

        $stmt = db_get_prepare_stmt($db_connect, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    if (isset($_GET['date_list'])) {
        $active_tab = $_GET['date_list'];
        $sql = show_tasks_by_date($user_profile, $active_tab);
        $tasks = mysqli_query($db_connect, $sql);
    }
}

require_once ('completed-task.php');

// Подключение темплейтов
$projects_list = include_template ('projects.php', [
    'all_tasks' => $all_tasks,
    'projects' => $projects
]);

if (isset($_SESSION['user'])) {
    $main_content = include_template('main.php', [
        'projects_list' => $projects_list,
        'tasks' => $tasks,
        'all_tasks' => $all_tasks,
        'projects' => $projects,
        'show_complete_tasks' => $show_complete_tasks,
        'is_cat_id' => $is_cat_id
    ]);
} else {
    $main_content = include_template('guest.php', []);
}

$layout_content = include_template('layout.php', [
    'content' => $main_content,
    'title' => 'Дела в порядке',
    'user_name' => $user_name
]);

// Вывод темплейтов
print($layout_content);
?>
