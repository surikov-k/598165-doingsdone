<?php

require_once('functions.php');
require_once('init.php');

if (!isset($_SESSION['user'])) {

    $index_content = include_template('guest.php', []);

    $layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'body_class' => ' body-background',
    'content' => $index_content,
    'sidebar' => '',
    'user' => []
    ]);

    print ($layout_content);
    die;
}

$current_user_id =$_SESSION['user']['id'];
$show_complete_tasks = '';

if(isset($_SESSION['show_completed'])) {
    $show_complete_tasks = $_SESSION['show_completed'];
}

if (isset($_GET['show_completed'])) {
    $referer_url = $_SERVER['HTTP_REFERER'];
    $_SESSION['show_completed'] = $_GET['show_completed'];
    $show_complete_tasks = $_SESSION['show_completed'];
    header("Location: " . $referer_url);
}


if (isset($_GET['check'])) {
    $referer_url = $_SERVER['HTTP_REFERER'];
    if(isset($_GET['task_id'])) {
        if(check_task_id($link, $_GET['task_id'], $current_user_id)) {
            change_task_status($link, $_GET['task_id'], $current_user_id);
        }

    }
    header("Location: " . $referer_url);
}


$filter = $_GET['filter'] ?? '';
$all_tasks = get_tasks($link, $current_user_id, 'all', 'all');

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

    if(!check_project_id($link, $_GET['id'], $current_user_id)) {
        $error =  "Проект не существует";
        $index_content = include_template('error.php', ['error' => $error]);
        http_response_code(404);
    } else {
        $tasks = get_tasks($link, $current_user_id, $_GET['id'], $filter);
        $index_content = include_template('index.php', [
            'tasks' => $tasks,
            'show_complete_tasks' =>  $show_complete_tasks
        ]);
    }

} else {
    $tasks = get_tasks($link, $current_user_id, 'all', $filter);
    $index_content = include_template('index.php', [
        'tasks' => $tasks,
        'show_complete_tasks' =>  $show_complete_tasks
    ]);
}

$sidebar = include_template('sidebar.php',[
    'projects' => $projects,
    'tasks' => $all_tasks,
]);

$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'body_class' => '',
    'projects' => $projects,
    'tasks' => $all_tasks,
    'content' => $index_content,
    'sidebar' => $sidebar,
    'user' => $_SESSION['user']
]);

print ($layout_content);

