<main class="content__main">
    <h2 class="content__main-heading">Регистрация аккаунта</h2>


    <form class="form" action="register.php" method="post">
    <div class="form__row">
        <?php
            $error_class = isset($errors['email']) ? ' form__input--error' : '';
            $email = isset($form['email']) ? $form['email'] : '';
        ?>
        <label class="form__label" for="email">E-mail <sup>*</sup></label>

        <input class="form__input <?= $error_class ?>" type="text" name="email" id="email" value="<?= $email ?>" placeholder="Введите e-mail">

        <?php if (isset($errors['email'])): ?>
            <p class="form__message"><?= $errors['email'] ?></p>
        <?php endif; ?>

    </div>

    <div class="form__row">
        <?php
            $error_class = isset($errors['password']) ? ' form__input--error' : '';
            $password = isset($form['password']) ? $form['password'] : '';
        ?>

        <label class="form__label" for="password">Пароль <sup>*</sup></label>

        <input class="form__input <?= $error_class ?>" type="password" name="password" id="password" value="<?= $password ?>" placeholder="Введите пароль">
        <?php if (isset($errors['password'])): ?>
            <p class="form__message"><?= $errors['password'] ?></p>
        <?php endif; ?>
    </div>

    <div class="form__row">
        <?php
            $error_class = isset($errors['name']) ? 'form__input--error' : '';
            $name = isset($form['name']) ? $form['name'] : '';
        ?>
        <label class="form__label" for="name">Имя <sup>*</sup></label>

        <input class="form__input <?= $error_class ?>" type="text" name="name" id="name" value="<?= $name ?>" placeholder="Введите имя">
        <?php if (isset($errors['name'])): ?>
            <p class="form__message"><?= $errors['name'] ?></p>
        <?php endif; ?>
    </div>

    <div class="form__row form__row--controls">
        <?php if (!empty($errors)): ?>
            <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
        <?php endif; ?>

        <input class="button" type="submit" name="" value="Зарегистрироваться">
    </div>
    </form>
</main>
