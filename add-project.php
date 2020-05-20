<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');
require_once('select-tasks.php');
require_once('users.php');
require_once('projects-list.php');

$projects_list = include_template ('projects.php', [
    'all_tasks' => $all_tasks,
    'projects' => $projects
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_project  = $_POST;
    $required = ['project_name'];
    $errors = [];

    $rules = [
        'project_name' => function($value) {
            return validateLength($value, 1, 128);
        }
    ];

    $project = filter_input_array(INPUT_POST, ['project_name' => FILTER_DEFAULT]);

    if (empty($project['project_name'])) {
            $errors['project_name'] = 'Поле не может быть пустым';
    }


    if (empty($errors)) {
        $name = mysqli_real_escape_string($db_connect, $new_project['project_name']);
        $sql = "SELECT id FROM projects WHERE project_name = '$name' AND user_id = '$user_profile'";
        $res = mysqli_query($db_connect, $sql);

        if (mysqli_num_rows($res) > 0) {
            $errors['project_name'] = 'Название не должно повторятся';
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

$layout_content = include_template('layout.php', [
    'content' => $main_content,
    'title' => 'Добавить новый проект',
    'user_name' => $user_name
]);

print($layout_content);
?>
