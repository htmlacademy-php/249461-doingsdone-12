<?php
    //Функция вывода всех тасков
    function show_tasks ($user_id) {
        $sql = 'SELECT tasks.id, status, task_name, run_to, project_id, file_name, file_path FROM tasks ' . 'JOIN projects ON tasks.project_id = projects.id ' . 'WHERE tasks.user_id = "' . $user_id . '"';

        return $sql;
    }

    //Функция вывода тасков по заданой категории
    function show_cat_tasks ($user_id, $cat_id) {
        $sql = 'SELECT tasks.id, status, task_name, run_to, project_id, file_name, file_path FROM tasks ' . 'JOIN projects ON tasks.project_id = projects.id ' . 'WHERE tasks.user_id = "' . $user_id . '" AND project_id = "' . $cat_id . '"';

        return $sql;
    }

    //Функция вывода тасков по заданой дате (Табы)
    function show_tasks_by_date ($user_id, $tab) {

        if ($tab == 'today') {
            $sql = 'SELECT tasks.id, status, task_name, run_to, project_id, file_name, file_path FROM tasks ' . 'WHERE tasks.user_id = "' . $user_id . '" AND run_to = CURDATE()';
        } elseif ($tab == 'tomorrow') {
            $sql = 'SELECT tasks.id, status, task_name, run_to, project_id, file_name, file_path FROM tasks ' . 'WHERE tasks.user_id = "' . $user_id . '" AND run_to = ADDDATE(CURDATE(),INTERVAL 1 DAY)';
        } elseif ($tab == 'expired') {
            $sql = 'SELECT tasks.id, status, task_name, run_to, project_id, file_name, file_path FROM tasks ' . 'WHERE tasks.user_id = "' . $user_id . '" AND run_to < CURDATE()';
        }

        return $sql;
    }
?>
