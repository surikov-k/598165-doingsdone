<?php
    require_once('init.php');
    require_once('functions.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $form = $_POST;

        $required = ['email', 'password'];
        $errors = [];
        foreach( $required as $field) {
            if(empty($form[$field])) {
                $errors[$field] = 'Это поле надо заполнить';
            }
        }
        if (!empty($form['email'])) {
            $email = mysqli_real_escape_string($link, $form['email']);
            $user = check_email_exists($link, $email);

            if(!$user) {
                $errors['email'] = 'Такой пользователь не найден';
            } else {
                if (password_verify($form['password'], $user['password'])) {
                    $_SESSION['user'] = $user;
                    header("Location: /index.php");
                } else {
                    $errors['password'] = 'Неверный пароль';
                }
            }
        }


        if (count($errors)) {
             $page_content = include_template('form-authorization.php', ['form' => $form, 'errors' => $errors]);
        }

    } else {
        if (isset($_SESSION['user'])) {
            header("Location: /index.php");
        } else {
            $page_content = include_template('form-authorization.php', []);
        }
    }

print($page_content);
