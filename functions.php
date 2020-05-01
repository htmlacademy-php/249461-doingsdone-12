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

    //Функция получения значений из POST запроса
    function getPostVal($name) {
        return filter_input(INPUT_POST, $name);
    }
    //Функция получения значений из GET запроса
    function getGETVal($name) {
        return filter_input(INPUT_GET, $name);
    }

    // функции валидации
    function validateCategory($id, $allowed_list) {
        if (!in_array($id, $allowed_list)) {
            return "Указана несуществующая категория";
        }

        return null;
    }

    function validateLength($value, $min, $max) {
        if ($value) {
            $len = strlen($value);
            if ($len < $min or $len > $max) {
                return "Значение должно быть от $min до $max символов";
            }
        }

        return null;
    }

?>
