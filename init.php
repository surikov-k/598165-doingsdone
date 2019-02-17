<?php
$link = mysqli_connect('localhost', 'root', 'root', 'doingsdone');
mysqli_set_charset($link, 'utf8');

$categories = [];
$tasks = [];

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
