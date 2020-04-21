<?php
    //Функция вывода всех тасков
    function show_tasks ($user_id) {
        $sql = 'SELECT tasks.id, status, task_name, run_to, project_id FROM tasks ' . 'JOIN projects ON tasks.project_id = projects.id ' . 'WHERE tasks.user_id = "' . $user_id . '"';

        return $sql;
    }

    //Функция вывода тасков по заданой категории
    function show_cat_tasks ($user_id, $cat_id) {
        $sql = 'SELECT tasks.id, status, task_name, run_to, project_id FROM tasks ' . 'JOIN projects ON tasks.project_id = projects.id ' . 'WHERE tasks.user_id = "' . $user_id . '" AND project_id = "' . $cat_id . '"';

        return $sql;
    }
?>
