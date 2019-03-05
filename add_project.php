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
    $content = include_template('add_project.php', ['projects' => $projects]);
    $all_tasks = get_tasks($link, $current_user_id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $error = '';
        $project = $_POST['project_name'];
        if (empty($project)) {
            $error = 'Это поле надо заполнить';
        } else {
            if (get_project($link, $project, $current_user_id)) {
                $error = 'У вас уже есть проект с таким названием';
            }
        }

        if (!empty($error)) {
            $content = include_template('add_project.php', ['error' => $error, 'project' => $project]);
        } else {
            if (create_project($link, $project, $current_user_id)) {
                header('Location: index.php');
            } else {
                $content = include_template('error.php', ['error' => $mysqli_error($link)]);
            }
        }
    }
}

$sidebar = include_template('sidebar.php', [
    'projects' => $projects,
    'tasks' => $all_tasks,
]);

$layout_content = include_template('layout.php', [
    'title' => 'Добавить проект - Дела в порядке',
    'projects' => $projects,
    'tasks' => $all_tasks,
    'content' => $content,
    'sidebar' => $sidebar,
    'user' => $user
]);

print($layout_content);
