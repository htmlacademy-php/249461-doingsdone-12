<?php
    $mail_theme = "Уведомление от сервиса «Дела в порядке»";
    $nail_test = "Уважаемый, %имя пользователя%. У вас запланирована задача %имя задачи% на %время задачи%";

    require_once ('vendor/autoload.php');
    require_once ('init.php');
    require_once ('functions.php');
    require_once ('helpers.php');
    require_once ('select-tasks.php');

    $transport = new Swift_SmtpTransport("smtp.mailtrap.io", 2525);
    $transport->setUsername("d640da00332e4b");
    $transport->setPassword("d87dcad8357b5a");

    $mailer = new Swift_Mailer($transport);

    $sql = 'SELECT id, email, user_name FROM users';
    $res = mysqli_query($db_connect, $sql);
    $all_users = mysqli_fetch_all($res, MYSQLI_ASSOC);

    foreach ($all_users as $key => $value) {
        $sql = "SELECT * FROM tasks WHERE status = 0 AND run_to = CURDATE() AND tasks.user_id = " . $value['id'];
        $res = mysqli_query($db_connect, $sql);
        $task_for_user_today = mysqli_fetch_all($res, MYSQLI_ASSOC);

        $user_name = $value['user_name'];

        if (!empty($task_for_user_today)) {

            $message = new Swift_Message();
            $message->setSubject("Уведомление от сервиса «Дела в порядке»");
            $message->setFrom(['keks@phpdemo.ru' => 'Дела в порядке']);
            $message->setTo([$value['email'] => $user_name]);

            $msg_content = include_template('message.php', [
                'tasks_for_today' => $task_for_user_today,
                'user_name' => $value['user_name']
            ]);
            $message->setBody($msg_content, 'text/html');

            $result = $mailer->send($message);
        }
    }
?>
