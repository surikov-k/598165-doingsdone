<?php

    require_once('utils.php');
    require_once('db.php');

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


    function compare_date_with_today ($date) {
        $date = strtotime(date_format(date_create_from_format('Y.m.d', $date), 'Y-m-d'));
        $date = $date + 24 * 60 * 60;
        $diff = $date  - time();
        return $diff;
    }

    function check_project_id($link, $project_id, $user_id) {
        $sql_project =
            "SELECT *
            FROM projects
            WHERE id = '$project_id' AND user_id = '$user_id';";

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
            return $file_url;
        }
    }


    function check_email_exists($link, $email) {
        $sql_email =
            "SELECT *
            FROM users
            WHERE email = '$email';";

        $result_email = mysqli_query($link, $sql_email);

        if (!$result_email) {
                return null;
            }
        return mysqli_fetch_array($result_email, MYSQLI_ASSOC);
    }
