<?php
require_once('functions.php');
require_once('init.php');

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

$CURRENT_USER_ID =1;

if (!$link) {
    $error = mysqli_connect_error();
    $index_content = include_template('error.php', ['error' => $error]);
} else {
    $sql_projects = 'SELECT title FROM projects WHERE user_id = "' . $CURRENT_USER_ID . '";';
    $result_projects = mysqli_query($link, $sql_projects);

    $sql_tasks = 'SELECT * FROM tasks JOIN projects ON project_id = projects.id WHERE tasks.user_id ="' . $CURRENT_USER_ID . '";';
    $result_tasks = mysqli_query($link, $sql_tasks);

    if($result_projects) {
        $categories = mysqli_fetch_all($result_projects, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        $index_content = include_template('error.php', ['error' => $error]);
    }

    if($result_tasks) {
        $tasks = mysqli_fetch_all($result_tasks, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        $index_content = include_template('error.php', ['error' => $error]);
    }
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

