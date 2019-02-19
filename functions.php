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

