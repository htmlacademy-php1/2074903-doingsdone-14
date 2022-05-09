<h2 class="content__main-heading">Добавление проекта</h2>

<form class="form"  action="new-project.php" method="post" autocomplete="off">
    <div class="form__row">
    <label class="form__label" for="project_name">Название <sup>*</sup></label>
    <?php $classname = !empty($errors['name']) ? "form__input--error" : ""; ?>

    <input class="form__input <?= $classname; ?>" type="text" name="name" id="project_name" value="<?= get_post_value('name'); ?>" placeholder="Введите название проекта">
    <?php if (!empty($errors['name'])): ?><p class="form__message"><?= $errors['name']; ?></p><?php endif; ?>
    </div>

    <div class="form__row form__row--controls">
    <?php if (!empty($errors)): ?>
    <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
    <?php endif; ?>

    <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
