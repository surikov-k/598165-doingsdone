<?php
require_once('utils.php');
require_once('db.php');

function count_tasks($tasks, $project_title)
{
    $counter = 0;
    foreach ($tasks as $task) {
        if ($task['project_title'] === $project_title) {
            $counter++;
        }
    }
    return $counter;
}

function is_due_date($date)
{
    if (!$date) {
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

function compare_date_with_today($date)
{
    $date = strtotime(date_format(date_create_from_format('Y.m.d', $date), 'Y-m-d'));
    $date = $date + 24 * 60 * 60;
    $diff = $date  - time();
    return $diff;
}

function save_file()
{
    if (isset($_FILES['preview']['name'])) {
        $file_name = $_FILES['preview']['name'];
        $file_path = __DIR__ . '/';
        $file_url =  $file_name;
        move_uploaded_file($_FILES['preview']['tmp_name'], $file_path . $file_name);
        return $file_url;
    }
}

function add_filter_to_url($param = null)
{
    if ($param) {
        return '?' . http_build_query(array_merge($_GET, array('filter'=> $param)));
    }

    $url = $_SERVER['REQUEST_URI'];
    $parsed = parse_url($url);

    if (isset($parsed['query'])) {
        $query = $parsed['query'];
        parse_str($query, $params);
        unset($params['filter']);
        $string = http_build_query($params);
        $string = $string ? '?' . $string : '/';
        return $string;
    }

    return '/';
}

function add_active_class($string)
{
    if (isset($_GET['filter'])) {
        if ($_GET['filter'] === $string) {
            return ' tasks-switch__item--active';
        } else {
            return '';
        }
    } else {
        if (!$string) {
            return ' tasks-switch__item--active';
        }
    }
    return '';
}
