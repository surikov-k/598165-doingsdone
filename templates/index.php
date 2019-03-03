<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="post">
        <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="<?= add_filter_to_url() ?>" class="tasks-switch__item <?= add_active_class('') ?>">Все задачи</a>
            <a href="<?= add_filter_to_url('today') ?>" class="tasks-switch__item <?= add_active_class('today')?>">Повестка дня</a>
            <a href="<?= add_filter_to_url('tomorrow') ?>" class="tasks-switch__item <?= add_active_class('tomorrow')?>">Завтра</a>
            <a href="<?= add_filter_to_url('overdue') ?>" class="tasks-switch__item <?= add_active_class('overdue')?>">Просроченные</a>
        </nav>

        <label class="checkbox">
            <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_complete_tasks):?> checked <?php endif ?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
    <?php
        foreach($tasks as $task) {

            if(!$task['completed'] || $show_complete_tasks) {
                $tr_class_list = 'tasks__item task';
                $checked = null;

                if(!$task['completed'] && is_due_date($task['due_date'])) {
                    $tr_class_list .= ' task--important';
                }

                if ($task['completed']) {
                    $tr_class_list .= ' task--completed';
                    $checked = 'checked';
                }?>

                <tr class="<?= $tr_class_list ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox" >
                            <input class="checkbox__input visually-hidden" type="checkbox" <?= $checked ?> value=<?= $task['id']?>>
                            <span class="checkbox__text"> <?= htmlspecialchars($task['title']) ?></span>
                        </label>
                    </td>

                    <?php
                        $link_class= '';
                        $file_link= '';

                        if (isset($task['attachment']) && $task['attachment'] !== '') {
                            $file_link = $task['attachment'];
                            $link_class = 'download-link';
                            }
                    ?>

                    <td class="task__file"><a class="<?= $link_class ?>" href="<?= $file_link ?>"><?= $file_link ?></a></td>


                    <td class="task__date"><?= date_format(date_create(htmlspecialchars($task['due_date'])), 'd/m/Y'); ?></td>
                    <td class="task__controls"></td>
                </tr>
            <?php }
            }?>
    </table>
</main>
