<?php
    /**
     * @param integer $user_id Id текущего пользователя
     * @return "sql" запрос на получение всех тасков
     */
    function show_tasks ($user_id) {
        $sql = 'SELECT tasks.id, status, task_name, run_to, project_id, file_name, file_path FROM tasks ' . 'JOIN projects ON tasks.project_id = projects.id ' . 'WHERE tasks.user_id = "' . $user_id . '"';

        return $sql;
    }

    /**
     * @param integer $user_id Id текущего пользователя
     * @param integer $cat_id Id категории у текущего пользователя
     * @return "sql" запрос на получение всех тасков по выбранной категории
     */
    function show_cat_tasks ($user_id, $cat_id) {
        $sql = 'SELECT tasks.id, status, task_name, run_to, project_id, file_name, file_path FROM tasks ' . 'JOIN projects ON tasks.project_id = projects.id ' . 'WHERE tasks.user_id = "' . $user_id . '" AND project_id = "' . $cat_id . '"';

        return $sql;
    }

    /**
     * @param integer $user_id Id текущего пользователя
     * @param string $tab Строка с привязкой к дате (today, tomorrow, expired)
     * @return "sql" запрос на получение всех тасков с привязкой к дате (today, tomorrow, expired)
     */
    function show_tasks_by_date ($user_id, $tab) {

        if ($tab === 'today') {
            $sql = 'SELECT tasks.id, status, task_name, run_to, project_id, file_name, file_path FROM tasks ' . 'WHERE tasks.user_id = "' . $user_id . '" AND run_to = CURDATE()';
        } elseif ($tab === 'tomorrow') {
            $sql = 'SELECT tasks.id, status, task_name, run_to, project_id, file_name, file_path FROM tasks ' . 'WHERE tasks.user_id = "' . $user_id . '" AND run_to = ADDDATE(CURDATE(),INTERVAL 1 DAY)';
        } elseif ($tab === 'expired') {
            $sql = 'SELECT tasks.id, status, task_name, run_to, project_id, file_name, file_path FROM tasks ' . 'WHERE tasks.user_id = "' . $user_id . '" AND run_to < CURDATE()';
        }

        return $sql;
    }
?>
