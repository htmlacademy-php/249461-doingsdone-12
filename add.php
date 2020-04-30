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

$cat_id = array_column($projects, 'id');

/*if (!$db_connect) {
    $error = mysqli_connect_error();
    print("Ошибка Базы данных " . $error);
} else {


    $cat_id = array_column($projects, 'id');
}*/

$projects_list = include_template ('projects.php', [
    'all_tasks' => $all_tasks,
    'projects' => $projects
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_task = $_POST;
    //Обязательные поля
    $required = ['task_name', 'project_id'];
    //Ошибки валидации
    $errors = [];

    //Функции-помощники для валидации и поля, которые они должны обработать
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

    //Получение данных из формы
    $task = filter_input_array(INPUT_POST, ['task_name' => FILTER_DEFAULT, 'project_id' => FILTER_DEFAULT, 'run_to' => FILTER_DEFAULT], true);
    if ($task['run_to'] == '') {
        $task['run_to'] = NULL;
    }
    //Применяем фун-ии валидации к полям формы
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
        //Имя файла
        $file_name = $_FILES['file']['name'];
        $new_task['file_name'] = $file_name;

        if ($file_name) {
            //Имя дирктории и путь создания для сохранения загруженого файла
            $dir_name = uniqid();
            $new_dir = mkdir('uploads/' . $dir_name, 0777);

            // Путь к файлу,
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

    //Проверяем длинну массива с ошибками
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

// Подключение темплейтов
$layout_content = include_template('layout.php', [
    'content' => $main_content,
    'title' => 'Добавление задачи',
    'user_name' => $user_name
]);

// Вывод темплейтов
print($layout_content);
?>
