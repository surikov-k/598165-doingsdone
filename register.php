<?php
require_once('init.php');
require_once('functions.php');

$content = include_template('register.php', []);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['email', 'password', 'name'];
    $errors = [];
    $form = $_POST;

    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    if (!empty($form['email']) && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'E-mail введён некорректно';
    } else {
        $email = mysqli_real_escape_string($link, $form['email']);
        if(get_user($link, $email)) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

    if(count($errors)) {
        $content = include_template('register.php', ['form' => $form, 'errors' => $errors]);
    } else {
        $user = create_new_user($link, $form);
        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: index.php');
        } else {
            $content = include_template('error.php', ['error' => mysqli_error($link)]);
        }
    }
}

$layout_content = include_template('layout.php', [
    'title' => 'Регистрация - Дела в порядке',
    'projects' => [],
    'tasks' => [],
    'content' => $content
]);

print ($layout_content);
