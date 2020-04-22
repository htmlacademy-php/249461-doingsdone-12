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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_task = $_POST;

    if (isset($_FILES['file'])) {
        /*$file_type = getExtension($_FILES['file']['name']);
        $file_name = uniqid() . "." . $file_type;
        $new_task['path'] = $file_name;*/
        $file_name = $_FILES['file']['name'];
        $new_task['path'] = $file_name;
        move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $file_name);
    }

    $sql = 'INSERT INTO tasks (create_date, status, task_name, project_id, user_id, run_to, path) VALUES (NOW(), 0, ?, ?, 1, ?, ?)';

    $stmt = db_get_prepare_stmt($db_connect, $sql, $new_task);
    $res = mysqli_stmt_execute($stmt);
    var_dump($new_task);
    if ($res) {
        $res_id = mysqli_insert_id($db_connect);

        var_dump($res_id);
    } else {
        $error = mysqli_error($db_connect);
        print("Ошибка: " . $error);
    }
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
