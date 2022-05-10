<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form"  action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
    <div class="form__row">
    <label class="form__label" for="name">Название <sup>*</sup></label>
    <?php $classname = !empty($errors['name']) ? "form__input--error" : ""; ?>

    <input class="form__input <?= $classname; ?>" type="text" name="name" id="name" value="<?= get_post_value('name'); ?>" placeholder="Введите название">
    <?php if (!empty($errors['name'])): ?><p class="form__message"><?= $errors['name']; ?></p><?php endif; ?>
    </div>

    <div class="form__row">
    <label class="form__label" for="project">Проект <sup>*</sup></label>
    <?php $classname = !empty($errors['project_id']) ? "form__input--error" : ""; ?>

    <select class="form__input form__input--select <?= $classname; ?>" name="project_id" id="project">
    <?php foreach ($projects as $project): ?>
        <option value="<?= $project['id'] ?>"<?php if ($project['id'] === get_post_value('project_id')): ?>selected<?php endif; ?>>
            <?= $project['name']; ?>
        </option>
    <?php endforeach; ?>
    </select>

    <?php if (!empty($errors['project_id'])): ?><p class="form__message"><?= $errors['project_id']; ?></p><?php endif; ?>
    </div>

    <div class="form__row">
    <?php $classname = !empty($errors['dt_deadline']) ? "form__input--error" : ""; ?>

    <label class="form__label" for="date">Дата выполнения</label>

    <input class="form__input form__input--date <?= $classname; ?>" type="text" name="dt_deadline" id="date" value="<?= get_post_value('dt_deadline'); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
    <?php if (!empty($errors['dt_deadline'])): ?><p class="form__message"><?= $errors['dt_deadline']; ?></p><?php endif; ?>
    </div>

    <div class="form__row">
    <label class="form__label" for="file">Файл</label>

    <div class="form__input-file">
        <input class="visually-hidden" type="file" name="file" id="file" value="<?= get_post_value('file'); ?>">

        <label class="button button--transparent" for="file">
        <span>Выберите файл</span>
        </label>
    </div>
    <?php if (!empty($errors['file'])): ?><p class="form__message"><?= $errors['file']; ?></p><?php endif; ?>
    </div>

    <div class="form__row form__row--controls">
        <?php if (!empty($errors)): ?>
        <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
        <?php endif; ?>

        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
