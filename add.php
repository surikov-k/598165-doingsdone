<?php
$CURRENT_USER_ID = 1;

require_once('functions.php');
require_once('init.php');

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
} else {
    $projects = get_projects($link, $CURRENT_USER_ID);
    $content = include_template('add.php', ['projects' => $projects]);

    $all_tasks = get_tasks($link, $CURRENT_USER_ID);
    if (!$all_tasks) {
        $error = mysqli_error($link);
        $content = include_template('error.php', ['error' => $error]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $required = ['name', 'project', 'date'];
        $errors = [];

        $task = $_POST;
        $file_url = '';
        $due_date = $task['date'];
        foreach ($required as $key) {
            if (empty($_POST[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        }

        if (!empty($task ['date'])) {
            if (compare_date_with_today($task['date']) < 0) {
                $errors['date'] = 'Выберете дату котороя не раньше сегодняшнего дня';
            }
        }

        if(!check_project_id($link, $task['project'], $CURRENT_USER_ID)) {
            $errors['project'] = 'Выберете существующий проект';
        }

        if (count($errors)) {
            $content = include_template('add.php', ['task' => $task, 'projects' => $projects, 'errors' => $errors]);
        } else {

            if ($_FILES['preview']['name']) {
                $file_name = $_FILES['preview']['name'];
                $file_path = __DIR__ . '/';
                $file_url =  $file_name;
                move_uploaded_file($_FILES['preview']['tmp_name'], $file_path . $file_name);
            }

            $sql = 'INSERT INTO tasks (due_date, title, attachment, project_id, user_id) VALUES ( ?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$due_date, $task['name'], $file_url , $task['project'], $CURRENT_USER_ID]);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                $task = null;
                header('Location: index.php');
            } else {
                $content = include_template('error.php', ['error' => mysqli_error($link)]);
            }
        }
    }
}

$layout_content = include_template('layout.php', [
    'title' => 'Добавить задачу - Дела в порядке',
    'projects' => $projects,
    'tasks' => $all_tasks,
    'content' => $content
]);

print ($layout_content);
