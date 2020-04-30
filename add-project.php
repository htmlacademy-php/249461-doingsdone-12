<?php

// Подключение функций
require_once('functions.php');

// Файл с функцией подключения темплейтов
require_once('helpers.php');

// Подключение к БД
require_once('init.php');

// Функции с запросами на получение списка тасков
require_once('select-tasks.php');

// Данные о пользователе
require_once('users.php');

// Данные по проетам для сайдбара
require_once('projects-list.php');

$projects_list = include_template ('projects.php', [
    'all_tasks' => $all_tasks,
    'projects' => $projects
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_project  = $_POST;
    //Обязательные поля
    $required = ['project_name'];
    //Ошибки валидации
    $errors = [];

    $rules = [
        'project_name' => function($value) {
            return validateLength($value, 1, 128);
        }
    ];

    // Получаем данные из формы
    $project = filter_input_array(INPUT_POST, ['project_name' => FILTER_DEFAULT]);

    //Проверка на пустые поля
    foreach ($required as $field) {
        if (empty($project[$field])) {
            $errors[$field] = 'Поле не может быть пустым';
        }
    }

    $new_project['user_id'] = $user_profile;

    $errors = array_filter($errors);

    if (count($errors)) {
        $main_content = include_template('add-project.php', [
            'project' => $project,
            'errors' => $errors,
            'projects' => $projects,
            'projects_list' => $projects_list
        ]);
    } else {
        $new_project['user_id'] = $user_profile;
        $sql = 'INSERT INTO projects (project_name, user_id) VALUES (?, ?)';

        $stmt = db_get_prepare_stmt($db_connect, $sql, $new_project);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            header("Location: /");
        } else {
            $error = mysqli_error($db_connect);
            print("Ошибка: " . $error);
        }
    }
} else {
    $main_content = include_template ('add-project.php', [
        'projects_list' => $projects_list,
        'projects' => $projects
    ]);
}

// Подключение темплейтов
$layout_content = include_template('layout.php', [
    'content' => $main_content,
    'title' => 'Добавить новый проект',
    'user_name' => $user_name
]);

// Вывод темплейтов
print($layout_content);

?>
