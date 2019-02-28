<?php

require_once('functions.php');
require_once('init.php');

if (!isset($_SESSION['user'])) {

    $index_content = include_template('guest.php', []);

    $layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'content' => $index_content,
    'user' => []
    ]);

    print ($layout_content);
    die;
}

$current_user_id =$_SESSION['user']['id'];

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);


$all_tasks = get_tasks($link, $current_user_id);

if (!$all_tasks) {
    $error = mysqli_error($link);
    $index_content = include_template('error.php', ['error' => $error]);
}

$projects = get_projects($link, $current_user_id);

if (!$projects) {
    $error = mysqli_error($link);
    $index_content = include_template('error.php', ['error' => $error]);
}

if (isset($_GET['id'])) {
    $tasks = get_tasks($link, $current_user_id, $_GET['id']);
    $index_content = include_template('index.php', [
        'tasks' => $tasks,
        'show_complete_tasks' =>  $show_complete_tasks
    ]);
    if(!$tasks) {
        $error =  "Проект " . htmlspecialchars($_GET['id']) . " не существует";
        $index_content = include_template('error.php', ['error' => $error]);
        http_response_code(404);
    }
} else {
    $tasks = $all_tasks;
    $index_content = include_template('index.php', [
        'tasks' => $tasks,
        'show_complete_tasks' =>  $show_complete_tasks
    ]);
}

$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'projects' => $projects,
    'tasks' => $all_tasks,
    'content' => $index_content,
    'user' => $_SESSION['user']
]);

print ($layout_content);

