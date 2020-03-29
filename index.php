<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

$projects = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];

$tasks = [
    [
        'task' => 'Собеседование в IT компании',
        'date' => '01.12.2019',
        'category' => 'Работа',
        'completed' => false
    ],
    [
        'task' => 'Выполнить тестовое задание',
        'date' => '25.12.2019',
        'category' => 'Работа',
        'completed' => false
    ],
    [
        'task' => 'Сделать задание первого раздела',
        'date' => '21.12.2019',
        'category' => 'Учеба',
        'completed' => true
    ],
    [
        'task' => 'Встреча с другом',
        'date' => '22.12.2019',
        'category' => 'Входящие',
        'completed' => false
    ],
    [
        'task' => 'Купить корм для кота',
        'date' => null,
        'category' => 'Домашние дела',
        'completed' => false
    ],
    [
        'task' => 'Заказать пиццу',
        'date' => null,
        'category' => 'Домашние дела',
        'completed' => false
    ],
];

// Функция посчета кол-ва задач по категориям.
function count_tasks (
    $arr_tasks, $categoryTask
) {
    $count = 0;
    foreach ($arr_tasks as $key => $val) {
        if ($val['category'] == $categoryTask) {
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

// Функция подключения темплейтов
require_once('helpers.php');

// Подключение темплейтов
$main_content = include_template ('main.php', [
    'tasks' => $tasks,
    'projects' => $projects]);

$layout_content = include_template('layout.php', [
    'content' => $main_content,
    'title' => 'Дела в порядке'
]);

// Вывод темплейтов
print($layout_content);

?>
