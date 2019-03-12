<?php
/**
 * Возвращает список проектов
 * @param resource $link Идентификатор соединения
 * @param int $user_id Индетификатор пользователя
 * @return array Список проектов
 */
function get_projects($link, $user_id)
{
    $sql_projects =
        "SELECT id, title
        FROM projects
        WHERE user_id = '$user_id';";

    $result_projects = mysqli_query($link, $sql_projects);

    if (!$result_projects) {
        return null;
    }
    return mysqli_fetch_all($result_projects, MYSQLI_ASSOC);
}

/**
 * Возвращает список задач или конкретную задачу
 * @param resource $link Идентификатор соединения
 * @param int $user_id Индетификатор пользователя
 * @param int $task_id Индетификатор задачи
 * @return array Список задач
 */
function get_tasks($link, $user_id, $task_id = 'all', $filter = 'all')
{
    switch ($filter) {
            case 'today':
                $date_condition = " AND t.due_date = CURDATE() ";
                break;
            case 'tomorrow':
                $date_condition = " AND t.due_date = CURDATE() + 1";
                break;
            case 'overdue':
                $date_condition = " AND t.due_date < CURDATE()";
                break;
            default:
            $date_condition = "";
        }

    if ($task_id !== 'all') {
        $task_conditon = " AND t.project_id = '$task_id' ";
    } else {
        $task_conditon = "";
    }

    $sql =
        "SELECT t.*, p.title AS project_title
        FROM tasks t
        JOIN projects p ON t.project_id = p.id
        WHERE t.user_id = '$user_id' " .
        $date_condition .
        $task_conditon .
        " ORDER BY t.create_time DESC;";


    $result = mysqli_query($link, $sql);

    if (!$result) {
        return null;
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Создает проект
 * @param resource $link Идентификатор соединения
 * @param string $title Название проекта
 * @param int $user_id Индетификатор пользователя
 * @return mixed $result_projects Результат операции
 */
function create_project($link, $title, $user_id)
{
    $sql_projects =
        "INSERT INTO projects (title, user_id)
        VALUES ('$title', '$user_id');";

    $result_projects = mysqli_query($link, $sql_projects);
    return $result_projects;
}

/**
 * Возвращает проект для конкретного пользователя
 * @param resource $link Идентификатор соединения
 * @param string $title Название проекта
 * @param int $user_id Индетификатор пользователя
 * @return array Проект
 */
function get_project($link, $title, $user_id)
{
    $sql_project =
    "SELECT *
    FROM projects
    WHERE title = '$title' AND user_id = '$user_id'";
    $result_project = mysqli_query($link, $sql_project);

    if (!$result_project) {
        return null;
    }

    return mysqli_fetch_array($result_project, MYSQLI_ASSOC);
}

/**
 * Возвращает  пользователя
 * @param resource $link Идентификатор соединения
 * @param int $user_id Индетификатор пользователя
 * @return array Пользователь
 */
function get_user($link, $email)
{
    $email = mysqli_real_escape_string($link, $email);
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

/**
 * Создает нового пользователя
 * @param resource $link Идентификатор соединения
 * @param array $form Данные формы
 * @return array $user Пользователь
 */
function create_new_user($link, $form)
{
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

/**
 * Проверяет существует ли проект
 * @param resource $link Идентификатор соединения
 * @param int $project_id Идентификатор проекта
 * @param int $user_id Идентификатор пользователя
 * @return array $user Пользователь
 */
function check_project_id($link, $project_id, $user_id)
{
    $sql_project =
        "SELECT *
        FROM projects
        WHERE id = '$project_id' AND user_id = '$user_id';";

    $result_project = mysqli_query($link, $sql_project);
    return mysqli_num_rows($result_project) > 0;
}

/**
 * Проверяет существует ли залача
 * @param resource $link Идентификатор соединения
 * @param int $task_id Идентификатор задачи
 * @param int $user_id Идентификатор пользователя
 * @return bool  Результат
 */
function check_task_id($link, $task_id, $user_id)
{
    $sql =
        "SELECT *
        FROM tasks
        WHERE id = '$task_id' AND user_id = '$user_id';";

    $result = mysqli_query($link, $sql);
    return mysqli_num_rows($result) > 0;
}

/**
 * Изменяет статус задачи
 * @param resource $link Идентификатор соединения
 * @param int $task_id Индетификатор задачи
 * @param int $user_id Индетификатор пользователя
 * @return bool  Результат
 */
function change_task_status($link, $task_id, $user_id)
{
    $sql =
    "UPDATE tasks
    SET completed = NOT completed
    WHERE id = '$task_id' AND user_id = '$user_id';
    ";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        return false;
    }
    return true;
}

/**
 * Добавляет задачу
 * @param resource $link Идентификатор соединения
 * @param string $due_date Дата выполнения
 * @param string $task_title Название задачи
 * @param string $file_url Путь к файлу
 * @param int $project Индетификатор проекта
 * @param int $user_id Индетификатор пользователя
 * @return bool  Результат
 */
function add_task($link, $due_date, $task_title, $file_url, $project, $user_id)
{
    $sql = 'INSERT INTO tasks (due_date, title, attachment, project_id, user_id) VALUES ( ?, ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, [$due_date, $task_title, $file_url, $project, $user_id]);
    return mysqli_stmt_execute($stmt);
}

/**
 * Поиск текста
 * @param resource $link Идентификатор соединения
 * @param string $search Строка поиска
 * @param int $user_id Индетификатор пользователя
 * @return array  Список задач
 */
function search_tasks($link, $search, $user_id)
{
    $sql =
    "SELECT * FROM tasks
    JOIN users ON tasks.user_id = '$user_id'
    WHERE MATCH(title) AGAINST(?);
    ";
    $stmt = db_get_prepare_stmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result= mysqli_stmt_get_result($stmt);
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if(empty($tasks)) {
        return false;
    }
    return $tasks;
}
