<main class="content__main">
        <h2 class="content__main-heading">Добавление проекта</h2>

        <form class="form"  action="add_project.php" method="post">
          <div class="form__row">
              <?php
                $error_class = isset($error) ? ' form__input--error' : '';
                $project = isset($project) ? $project : '';
              ?>
            <label class="form__label" for="project_name">Название <sup>*</sup></label>

            <input class="form__input <?= $error_class?>" type="text" name="project_name" id="project_name" value="<?= $project ?>" placeholder="Введите название проекта">
            <?php if (isset($error)): ?>
                <p class="form__message"><?= $error?></p>
            <?php endif ?>
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
          </div>
        </form>
      </main>
    </div>
  </div>
</div>
