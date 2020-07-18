<?php
require_once('functions.php');
require_once('helpers.php');
require_once('init.php');
require_once('select-tasks.php');
require_once('users.php');
require_once('projects-list.php');

$cat_id = array_column($projects, 'id');

$projects_list = include_template ('projects.php', [
    'all_tasks' => $all_tasks,
    'projects' => $projects
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_task = $_POST;
    $required = ['task_name', 'project_id'];
    $errors = [];

    $rules = [
        'task_name' => function($value) {
            return validateLength($value, 3, 200);
        },
        'project_id' => function($value) use ($cat_id) {
            return validateCategory($value, $cat_id);
        },
        'run_to' => function($value) {
            if ($value == NULL) {
                return;
        } else {
                $today = date('Y-m-d');

                if ($value >= $today) {
                    return is_date_valid('$value');
                } else {
                    return "Дата не может быть меньше текущей";
                }
            }
        }
    ];

    $task = filter_input_array(INPUT_POST, ['task_name' => FILTER_DEFAULT, 'project_id' => FILTER_DEFAULT, 'run_to' => FILTER_DEFAULT], true);
    if ($task['run_to'] == '') {
        $task['run_to'] = NULL;
    }
    foreach ($task as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле надо заполнить";
        }
    }

    $new_task['user_id'] = $user_profile;

    if (!empty($_FILES['file'])) {
        $file_name = $_FILES['file']['name'];
        $new_task['file_name'] = $file_name;

        if ($file_name) {
            $uploads = "uploads";
            if (!is_dir($uploads)) {
                mkdir($uploads, 0777);
            }

            $dir_name = uniqid();
            $new_dir = mkdir('uploads/' . $dir_name, 0777);

            $file_path = 'uploads/' . $dir_name . '/';
            $new_task['file_path'] = $file_path . $file_name;
            move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
        } else {
            $new_task['file_path'] = NULL;
            $new_task['file_name'] = NULL;
        }
    }

    if ($new_task['run_to'] == '') {
        $new_task['run_to'] = NULL;
    }

    $errors = array_filter($errors);

    if (count($errors)) {
        $main_content = include_template('add-task.php', [
            'task' => $task,
            'errors' => $errors,
            'projects' => $projects,
            'projects_list' => $projects_list
        ]);
    } else {
        $new_task['user_id'] = $user_profile;
        $sql = 'INSERT INTO tasks (create_date, status, task_name, project_id, run_to, user_id, file_name, file_path) VALUES (NOW(), 0, ?, ?, ?, ?, ?, ?)';

        $stmt = db_get_prepare_stmt($db_connect, $sql, $new_task);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $res_id = mysqli_insert_id($db_connect);

            header("Location: index.php");
        } else {
            $error = mysqli_error($db_connect);
            print("Ошибка: " . $error);
        }
    }
} else {
    $main_content = include_template ('add-task.php', [
        'projects_list' => $projects_list,
        'projects' => $projects
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $main_content,
    'title' => 'Добавление задачи',
    'user_name' => $user_name
]);

print($layout_content);
?>
