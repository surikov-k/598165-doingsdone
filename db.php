<?php
function get_projects($link, $user_id) {
    $sql_projects =
        "SELECT id, title
        FROM projects
        WHERE user_id = '$user_id';";

    $result_projects = mysqli_query($link, $sql_projects);

    if(!$result_projects) {
        return null;
    }
    return mysqli_fetch_all($result_projects, MYSQLI_ASSOC);
}


function get_tasks($link, $user_id, $task_id = null) {

    if ($task_id === null) {
        $sql_all_tasks =
            "SELECT t.*, p.title AS project_title
            FROM tasks t
            JOIN projects p ON t.project_id = p.id
            WHERE t.user_id = '$user_id' ORDER BY t.create_time DESC;";

        $result_all_task = mysqli_query($link, $sql_all_tasks);

        if (!$result_all_task) {
            return null;
        }
        return mysqli_fetch_all($result_all_task, MYSQLI_ASSOC);
    } else {
        $sql_tasks =
        "SELECT t.*, p.title AS project_title
        FROM tasks t
        JOIN projects p ON t.project_id = p.id
        WHERE t.user_id = '$user_id' AND t.project_id = '$task_id';";

        $result_tasks = mysqli_query($link, $sql_tasks);

        if (!$result_tasks) {
            return null;
        }
        return mysqli_fetch_all($result_tasks, MYSQLI_ASSOC);
    }
}


function create_project($link, $title, $user_id) {
    $sql_projects =
        "INSERT INTO projects (title, user_id)
        VALUES ('$title', '$user_id');";

    $result_projects = mysqli_query($link, $sql_projects);
    return $result_projects;
}


function create_new_user($link, $form) {
    $hash = password_hash($form['password'], PASSWORD_DEFAULT);
    $sql = 'INSERT INTO users (email, name,  password) VALUES (?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, [$form['email'], $form['name'], $hash]);
    $res = mysqli_stmt_execute($stmt);
    if (!$res) {
        return false;
    }
    $user = get_user($link, $form['email']);
    create_project($link, 'Входящие', $user['id']);
    return $user;
}


