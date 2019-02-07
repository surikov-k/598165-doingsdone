            <main class="content__main">
                <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="index.php" method="post">
                    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                        <a href="/" class="tasks-switch__item">Повестка дня</a>
                        <a href="/" class="tasks-switch__item">Завтра</a>
                        <a href="/" class="tasks-switch__item">Просроченные</a>
                    </nav>

                    <label class="checkbox">
                        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
                        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_complete_tasks):?> checked <?php endif ?>>
                        <span class="checkbox__text">Показывать выполненные</span>
                    </label>
                </div>

                <table class="tasks">
                <?php foreach($tasks as $task) {
                    if(!$task['completed'] || $show_complete_tasks) {
                    $tr_class_list = 'tasks__item task';
                    $checked = null;
                    if ($task['completed']) {
                        $tr_class_list.=' task--completed';
                        $checked = 'checked';
                }?>
                    <tr class="<?=$tr_class_list?>">
                            <td class="task__select">
                                <label class="checkbox task__checkbox">
                                    <input class="checkbox__input visually-hidden" type="checkbox" <?=$checked?>>
                                    <span class="checkbox__text"><?=htmlspecialchars($task['title']);?></span>
                                </label>
                            </td>
                            <td class="task__date"><?=htmlspecialchars($task['due_date']);?></td>
                            <td class="task__controls"></td>
                        </tr>
                <?php }
                }?>
                </table>
            </main>
