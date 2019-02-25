<main class="content__main">
        <h2 class="content__main-heading">Добавление задачи</h2>

        <form class="form"  action="add.php" method="post" enctype="multipart/form-data">
          <div class="form__row">
            <?php
                $error_class = isset($errors['name']) ? ' form__input--error' : '';
                $input_value = isset($task['name']) ? $task['name'] : '';
            ?>
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input
                class="form__input <?= $error_class ?>"
                type="text" name="name" id="name" value="<?= $input_value ?>" placeholder="Введите название">
            <?php
                if (isset($errors['name'])) {
                   print ('<p class="form__message">' . $errors['name'] . '</p>');
                }
            ?>
          </div>

          <div class="form__row">
            <?php
                $error_class = isset($errors['project']) ? ' form__input--error' : '';
            ?>
            <label class="form__label" for="project">Проект</label>

            <select class="form__input form__input--select <?= $error_class ?>" name="project" id="project">
                <?php foreach($projects as $project):?>
                    <option value="<?= $project['id'] ?>"
                        <?php
                            if(isset($task['project'])) {
                                if ($project['id'] === $task['project']) {
                                    print (' selected');
                                }
                            }
                        ?>
                        >
                        <?= htmlspecialchars($project['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php
                if (isset($errors['project'])) {
                   print ('<p class="form__message">' . $errors['project'] . '</p>');
                }
            ?>
          </div>

          <div class="form__row">
            <?php
                $error_class = isset($errors['date']) ? ' form__input--error' : '';
                $input_value = isset($task['date']) ? $task['date'] : '';
            ?>
            <label class="form__label" for="date">Дата выполнения</label>

            <input class="form__input form__input--date <?= $error_class ?>" type="date" name="date" id="date" value="<?= $input_value ?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
            <?php
                if (isset($errors['date'])) {
                   print ('<p class="form__message">' . $errors['date'] . '</p>');
                }
            ?>
          </div>

          <div class="form__row">
            <label class="form__label" for="preview">Файл</label>

            <div class="form__input-file">
              <input class="visually-hidden" type="file" name="preview" id="preview" value>

              <label class="button button--transparent" for="preview">
                <span>Выберите файл</span>
              </label>
            </div>
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
          </div>
        </form>
      </main>
