<?php
$CURRENT_USER_ID =1;

require_once('functions.php');
require_once('init.php');

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

if (!$link) {
    $error = mysqli_connect_error();
    $index_content = include_template('error.php', ['error' => $error]);
} else {
    $sql_projects = 'SELECT id, title FROM projects WHERE user_id = "' . $CURRENT_USER_ID . '";';
    $result_projects = mysqli_query($link, $sql_projects);

    $sql_all_tasks = 'SELECT t.*, p.title AS project_title FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.user_id = ' . $CURRENT_USER_ID . ';';
    $result_all_task = mysqli_query($link, $sql_all_tasks);
    if ($result_all_task) {
        $all_tasks = mysqli_fetch_all($result_all_task, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        $index_content = include_template('error.php', ['error' => $error]);
    }

    if($result_projects) {
        $projects = mysqli_fetch_all($result_projects, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        $index_content = include_template('error.php', ['error' => $error]);
    }

    if (isset($_GET['id'])) {
    $sql_tasks = 'SELECT t.*, p.title AS project_title FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.user_id = ' . $CURRENT_USER_ID . ' AND t.project_id = ' . $_GET['id'] . ';';
    $result_tasks = mysqli_query($link, $sql_tasks);

        if($result_tasks) {
            if ($result_tasks->num_rows) {
                $tasks = mysqli_fetch_all($result_tasks, MYSQLI_ASSOC);
                $index_content = include_template('index.php', [
                    'tasks' => $tasks,
                    'show_complete_tasks' =>  $show_complete_tasks
                ]);
            } else {
                $error = 'Нет такого проекта';
                $index_content = include_template('error.php', ['error' => $error]);
                http_response_code(404);
            }
        } else {
            $error = mysqli_error($link);
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
}

$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'projects' => $projects,
    'tasks' => $all_tasks,
    'content' => $index_content
]);

print ($layout_content);

