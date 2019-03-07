<?php
require_once('init.php');
require_once('functions.php');

$user = $_SESSION['user'] ?? [];
$projects = [];
$all_tasks =[];
$content = '';

if (!empty($user)) {
    $current_user_id = $user['id'];

    $projects = get_projects($link, $current_user_id);
    $content = include_template('add.php', ['projects' => $projects]);

    $all_tasks = get_tasks($link, $current_user_id);

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

        if (!check_project_id($link, $task['project'], $current_user_id)) {
            $errors['project'] = 'Выберете существующий проект';
        }

        if (count($errors)) {
            $content = include_template('add.php', ['task' => $task, 'projects' => $projects, 'errors' => $errors]);
        } else {
            $file_url= save_file();

            if (add_task($link, $due_date, $task['name'], $file_url, $task['project'], $current_user_id)) {
                header('Location: index.php');
            } else {
                $content = include_template('error.php', ['error' => mysqli_error($link)]);
            }
        }
    }
}

$sidebar = include_template('sidebar.php', [
    'projects' => $projects,
    'tasks' => $all_tasks,
]);

$layout_content = include_template('layout.php', [
    'title' => 'Добавить задачу - Дела в порядке',
    'projects' => $projects,
    'tasks' => $all_tasks,
    'content' => $content,
    'sidebar' => $sidebar,
    'user' => $user
]);

print($layout_content);
