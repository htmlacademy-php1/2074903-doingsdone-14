<h2 class="content__main-heading">Вход на сайт</h2>

<form class="form" action="auth.php" method="post" autocomplete="off">
    <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>
        <?php $classname = !empty($errors['email']) ? "form__input--error" : ""; ?>

        <input class="form__input <?= $classname; ?>"
            type="text" name="email" id="email" value="<?= get_post_value('email'); ?>"
            placeholder="Введите e-mail">
        <?php if (!empty($errors['email'])) : ?>
            <p class="form__message"><?= $errors['email']; ?></p>
        <?php endif; ?>
    </div>

    <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>
        <?php $classname = !empty($errors['password']) ? "form__input--error" : ""; ?>

        <input class="form__input <?= $classname; ?>"
            type="password" name="password" id="password" value="<?= get_post_value('password'); ?>"
            placeholder="Введите пароль">
        <?php if (!empty($errors['password'])) : ?>
            <p class="form__message"><?= $errors['password']; ?></p>
        <?php endif; ?>
    </div>

    <div class="form__row form__row--controls">
        <?php if (!empty($errors)) : ?>
            <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
        <?php endif; ?>

        <input class="button" type="submit" name="" value="Войти">
    </div>
        </form>
