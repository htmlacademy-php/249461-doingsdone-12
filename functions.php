<?php
    // Функция посчета кол-ва задач по категориям.
    function count_tasks (
    $arr_tasks, $categoryTask
    ) {
        $count = 0;
        foreach ($arr_tasks as $key => $val) {
            if ($val['project_id'] == $categoryTask) {
                $count++;
            }
        }

        return $count;
    }

    // Функция перевода спец.символов в мнемоники
    function protection_xss($str) {
        $text = htmlspecialchars($str);

        return $text;
    }

    // Функция проверки сколько часов осталось
    function timeleft ($enddate) {
        $secs_in_hour = 3600;

        $ind_ts = strtotime(date('d.m.Y'));
        $end_ts = strtotime($enddate);

        if ($enddate != null) {
            $time_left = ($end_ts - $ind_ts) / $secs_in_hour;
        } else {
            $time_left = 25;
        }

        return $time_left;
    }

?>
