<?php
require_once('functions.php');

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

$categories = ["Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];

$tasks = [
    [
        'title' => 'Собеседование в IT компании',
        // 'due_date' => '01.12.2019',
        'due_date' => '07.02.2019',
        'category' => 'Работа',
        'completed' => false
    ],
    [
        'title' => 'Выполнить тестовое задание',
        // 'due_date' => '25.12.2019',
        'due_date' => '25.12.2018',
        'category' => 'Работа',
        'completed' => false
    ],
    [
        'title' => 'Сделать задание первого раздела',
        'due_date' => '21.12.2019',
        'category' => 'Учеба',
        'completed' => true
    ],
    [
        'title' => 'Встреча с другом',
        'due_date' => '22.12.2019',
        'category' => 'Входящие',
        'completed' => false
    ],
    [
        'title' => 'Купить корм для кота',
        'due_date' => null,
        'category' => 'Домашние дела',
        'completed' => false
    ],
    [
        'title' => 'Заказать пиццу',
        'due_date' => null,
        'category' => 'Домашние дела',
        'completed' => false
    ]
];

function count_tasks($tasks, $task_category) {
    $counter = 0;
    foreach ($tasks as $task) {
        if ($task['category'] === $task_category) {
            $counter++;
        }
    }
    return $counter;
}

$index_content = include_template('index.php', [
    'tasks' => $tasks,
    'show_complete_tasks' =>  $show_complete_tasks
]);

$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'categories' => $categories,
    'tasks' => $tasks,
    'content' => $index_content
]);

print ($layout_content);
