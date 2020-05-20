<?php
    $sql = 'SELECT id, project_name FROM projects WHERE user_id = "' . $user_profile . '"';
    $result = mysqli_query($db_connect, $sql);
    if ($result) {
        $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($db_connect);
        print("Ошибка базы данных " . $error);
    }

    $tasks_arr = mysqli_query($db_connect, show_tasks($user_profile));

    if ($tasks_arr) {
        $all_tasks = mysqli_fetch_all($tasks_arr, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($db_connect);
        print("Ошибка базы данных " . $error);
    }

    if (isset($_GET['cat_id'])) {
        $cat_project = filter_input(INPUT_GET, 'cat_id', FILTER_SANITIZE_NUMBER_INT);
        $projects_id_arr = array_column($projects, 'id');
        $is_cat_id = in_array($cat_project, $projects_id_arr);
    }
?>
