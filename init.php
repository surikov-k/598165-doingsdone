<?php
$link = mysqli_connect('localhost', 'root', 'root', 'doingsdone');

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);

    $layout_content = include_template('layout.php', [
        'title' => 'Дела в порядке',
        'projects' => [],
        'tasks' => [],
        'content' => $content
    ]);
    print($layout_content);
    die;
} else {
    mysqli_set_charset($link, 'utf8');
}
session_start();
$errors = [];
