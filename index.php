<?php
$CURRENT_USER_ID =1;

require_once('functions.php');
require_once('init.php');

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

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

