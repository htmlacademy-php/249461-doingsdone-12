<?php

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET['task_id'])) {
        $input_task = $_GET['task_id'];
        if ($input_task > 0) {
            $sql = 'SELECT tasks.id, status, task_name, run_to, project_id, file_name, file_path FROM tasks WHERE tasks.user_id = "' . $user_profile . '" AND tasks.id = "' . $input_task . '"';
            $result = mysqli_query($db_connect, $sql);
            $task = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $task_status = array_column($task, 'status');
            if ($task_status[0] == 0) {
                $task_complete = 1;
            } else {
                $task_complete = 0;
            }

            $sql = 'UPDATE tasks SET status = "' . $task_complete . '" WHERE tasks.user_id = "' . $user_profile . '" AND tasks.id = "' . $input_task . '"';
            $result = mysqli_query($db_connect, $sql);

            if ($result) {
                header("Location: /index.php");
            }
        }
    }
}

$show_complete_tasks = 0;

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET['show_completed'])) {
        $show_completed = $_GET['show_completed'];
        if ($show_completed > 0) {
            $show_complete_tasks = 1;
        } else {
            $show_completed = 0;
            $show_complete_tasks = 0;
        }
    }
}

?>