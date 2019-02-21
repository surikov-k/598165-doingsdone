<?php
    function include_template($name, $data) {
        $name = 'templates/' . $name;
        $result = '';

        if (!is_readable($name)) {
            return $result;
        }

        ob_start();
        extract($data);
        require $name;

        $result = ob_get_clean();

        return $result;
    }


function count_tasks($tasks, $project_title) {
    $counter = 0;
    foreach ($tasks as $task) {
        if ($task['project_title'] === $project_title) {
            $counter++;
        }
    }
    return $counter;
}


function is_due_date($date) {
    if(!$date) {
        return false;
    }

    $now_ts = time();
    $due_ts = strtotime($date) + 24 * 60 * 60;
    $diff_hours = floor(($due_ts - $now_ts) / 60 / 60);

    if ($diff_hours <= 24) {
        return true;
    }

    return false;
}


function get_projects($link, $user_id) {
    $sql_projects =
        'SELECT
            id,
            title
        FROM projects
        WHERE
            user_id = "' . $user_id . '";';

    $result_projects = mysqli_query($link, $sql_projects);

    if(!$result_projects) {
        return null;
    }
    return mysqli_fetch_all($result_projects, MYSQLI_ASSOC);
}


function get_tasks($link, $user_id, $task_id = null) {

    if ($task_id === null) {
        $sql_all_tasks =
            'SELECT
                t.*,
                p.title AS project_title
            FROM tasks t
            JOIN projects p ON
                t.project_id = p.id
            WHERE
                t.user_id = ' . $user_id . ';';

        $result_all_task = mysqli_query($link, $sql_all_tasks);

        if (!$result_all_task) {
            return null;
        }
        return mysqli_fetch_all($result_all_task, MYSQLI_ASSOC);
    } else {
        $sql_tasks =
        'SELECT
            t.*,
            p.title AS project_title
        FROM tasks t
        JOIN projects p ON
            t.project_id = p.id
        WHERE
            t.user_id = ' . $user_id . ' AND t.project_id = ' . $task_id . ';';

        $result_tasks = mysqli_query($link, $sql_tasks);

        if (!$result_tasks) {
            return null;
        }
        return mysqli_fetch_all($result_tasks, MYSQLI_ASSOC);
    }
}
