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
                t.user_id = ' . $user_id . ' ORDER BY t.create_time DESC;';

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


function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}

function compare_date_with_today ($date) {
    $date = strtotime(date_format(date_create_from_format('Y.m.d', $date), 'Y-m-d'));
    $date = $date + 24 * 60 * 60;
    $diff = $date  - time();
    return $diff;
}

function check_project_id($link, $project_id, $user_id) {
    $sql_project =
        'SELECT
            *
        FROM projects
        WHERE
           id = ' . $project_id . ' AND user_id = ' . $user_id . ';';

    $result_project = mysqli_query($link, $sql_project);

     if (!$result_project) {
            return null;
        }
    return mysqli_num_rows($result_project) > 0;
}

function save_file() {
    if (isset($_FILES['preview']['name'])) {
        $file_name = $_FILES['preview']['name'];
        $file_path = __DIR__ . '/';
        $file_url =  $file_name;
        move_uploaded_file($_FILES['preview']['tmp_name'], $file_path . $file_name);
    }
}


function check_email_exists($link, $email) {
    $sql_email =
        'SELECT
            *
        FROM users
        WHERE
           email = "' . $email . '";';

    $result_email = mysqli_query($link, $sql_email);

     if (!$result_email) {
            return null;
        }
    return mysqli_num_rows($result_email) > 0;
}

