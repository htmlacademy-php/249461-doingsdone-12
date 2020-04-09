<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

/*$projects = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];

$tasks = [
    [
        'task' => 'Собеседование в IT компании',
        'date' => '01.04.2020',
        'category' => 'Работа',
        'completed' => false
    ],
    [
        'task' => 'Выполнить тестовое задание',
        'date' => '25.04.2020',
        'category' => 'Работа',
        'completed' => false
    ],
    [
        'task' => 'Сделать задание первого раздела',
        'date' => '21.03.2020',
        'category' => 'Учеба',
        'completed' => true
    ],
    [
        'task' => 'Встреча с другом',
        'date' => '30.03.2020',
        'category' => 'Входящие',
        'completed' => false
    ],
    [
        'task' => 'Купить корм для кота',
        'date' => null,
        'category' => 'Домашние дела',
        'completed' => false
    ],
    [
        'task' => 'Заказать пиццу',
        'date' => null,
        'category' => 'Домашние дела',
        'completed' => false
    ],
];*/

// Функция посчета кол-ва задач по категориям.
function count_tasks (
    $arr_tasks, $categoryTask
) {
    $count = 0;
    foreach ($arr_tasks as $key => $val) {
        if ($val['project_id'] == $categoryTask) {
            $count++;
        }
    }
    return $count;
}

// Функция перевода спец.символов в мнемоники
function protection_xss($str) {
    $text = htmlspecialchars($str);

    return $text;
}

// Функция проверки сколько часов осталось
function timeleft ($enddate) {
    $secs_in_hour = 3600;

    $ind_ts = strtotime(date('d.m.Y'));
    $end_ts = strtotime($enddate);

    if ($enddate != null) {
        $time_left = ($end_ts - $ind_ts) / $secs_in_hour;
    } else {
        $time_left = 25;
    }

    return $time_left;
}

// Для тестов выбор id пользователя
$user_profile = "1";

// Работа с БД
$db_connect = mysqli_connect('localhost', 'root', '', 'things_are_okay');
mysqli_set_charset($db_connect, 'utf8');

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
    $sql = 'SELECT tasks.id, status, task_name, run_to, project_id FROM tasks ' . 'JOIN projects ON tasks.project_id = projects.id ' . 'WHERE tasks.user_id = "' . $user_profile . '"';
    $tasks_arr = mysqli_query($db_connect, $sql);

    if ($tasks_arr) {
        $all_tasks = mysqli_fetch_all($tasks_arr, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($db_connect);
        print("Ошибка базы данных " . $error);
    }

    //сортировка задач по проектам
    if (isset($_GET['cat_id'])) {
        $projects_id_arr = [];
        foreach ($projects as $val) {
            array_push($projects_id_arr, $val['id']);
        }
        $is_cat_id = in_array($_GET['cat_id'], $projects_id_arr);
        //var_dump($test_arr);
        $cat_project = $_GET['cat_id'];
    }

    // Данные для показа тасков по всем категориям или одной выбранной
    if ($cat_project) {
        $sql = 'SELECT tasks.id, status, task_name, run_to, project_id FROM tasks ' . 'JOIN projects ON tasks.project_id = projects.id ' . 'WHERE tasks.user_id = "' . $user_profile . '" AND project_id = "' . $cat_project . '"';
    } else {
        $sql = 'SELECT tasks.id, status, task_name, run_to, project_id FROM tasks ' . 'JOIN projects ON tasks.project_id = projects.id ' . 'WHERE tasks.user_id = "' . $user_profile . '"';
    }
    $tasks_arr = mysqli_query($db_connect, $sql);

    if ($tasks_arr) {
        $tasks = mysqli_fetch_all($tasks_arr, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($db_connect);
        print("Ошибка базы данных " . $error);
    }
}


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
