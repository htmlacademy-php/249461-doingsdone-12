<?php

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['task_id'])) {
        $input_task = $_GET['task_id'];
        if ($input_task > 0) {
            $sql = 'SELECT tasks.id, status, task_name, run_to, project_id, file_name, file_path FROM tasks WHERE tasks.user_id = "' . $user_profile . '" AND tasks.id = "' . $input_task . '"';
            $result = mysqli_query($db_connect, $sql);
            $task = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $task_status = array_column($task, 'status');
            //var_dump($task_status[0]);
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

?>
