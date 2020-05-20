<?php
    /**
     * Функция посчета кол-ва задач по категориям.
     * @param array $arr_tasks Массив всех тасков
     * @param integer $categoryTask Id нужной категории
     *
     * @return integer Возвращает кол-во тасков по категории
     */
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

    /**
     * Функция перевода спец.символов в мнемоники.
     * @param string $str Строка текста
     *
     * @return string Возвращает строку с заменёнными спец.символами на мнемоники
     */
    function protection_xss($str) {
        $text = htmlspecialchars($str);

        return $text;
    }

    /**
     * Функция проверки сколько часов осталось до даты окончания задачи.
     * @param string $enddate Дата окончания задачи
     *
     * @return integer Возвращает кол-во часов оставшееся до завершения задачи. Если дата не указана значение по умолчанию 25.
     */
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

    /**
     * Функция получения значений из POST запроса.
     * @param string $name Input[name] из которого необходимо получить значение
     *
     * @return string Возвращает строку введенную пользователем, если форма отправленна с ошибкой.
     */
    function getPostVal($name) {
        return filter_input(INPUT_POST, $name);
    }

    /**
     * Функция получения значений из GET запроса.
     * @param string $name Получает строку, значение отправленное get запросом
     *
     * @return string Возвращает поленное значение
     */
    function getGETVal($name) {
        return filter_input(INPUT_GET, $name);
    }

    /**
     * Функция получения значений из POST запроса.
     * @param integer $id Id категории
     * @param array $allowed_list Список категория
     *
     * @return string Возвращает ошибку или пустую строку (null)
     */
    function validateCategory($id, $allowed_list) {
        if (!in_array($id, $allowed_list)) {
            return "Указана несуществующая категория";
        }

        return null;
    }


    /**
     * Функция проверки длинны введенного значения.
     * @param string $value Строка
     * @param integer $min Минимально допустимое кол-во символов
     * @param integer $max Максимально допустимое кол-во символов
     *
     * @return string Возвращает ошибку или пустую строку (null)
     */
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
