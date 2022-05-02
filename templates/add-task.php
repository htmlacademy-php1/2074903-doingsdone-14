    <div class="content">
      <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
          <ul class="main-navigation__list">
            <li class="main-navigation__list-item">
              <a class="main-navigation__list-item-link" href="#">Входящие</a>
              <span class="main-navigation__list-item-count">24</span>
            </li>

            <li class="main-navigation__list-item main-navigation__list-item--active">
              <a class="main-navigation__list-item-link" href="#">Работа</a>
              <span class="main-navigation__list-item-count">12</span>
            </li>

            <li class="main-navigation__list-item">
              <a class="main-navigation__list-item-link" href="#">Здоровье</a>
              <span class="main-navigation__list-item-count">3</span>
            </li>

            <li class="main-navigation__list-item">
              <a class="main-navigation__list-item-link" href="#">Домашние дела</a>
              <span class="main-navigation__list-item-count">7</span>
            </li>

            <li class="main-navigation__list-item">
              <a class="main-navigation__list-item-link" href="#">Авто</a>
              <span class="main-navigation__list-item-count">0</span>
            </li>
          </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button" href="form-project.html">Добавить проект</a>
      </section>

      <main class="content__main">
        <h2 class="content__main-heading">Добавление задачи</h2>

        <form class="form"  action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>
            <?php $classname = isset($errors['name']) ? "form__input--error" : ""; ?>

            <input class="form__input <?= $classname; ?>" type="text" name="name" id="name" value="<?= get_post_value('name'); ?>" placeholder="Введите название">
            <?php if (isset($errors['name'])): ?><p class="form__message"><?= $errors['name']; ?></p><?php endif; ?>
          </div>

          <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>
            <?php $classname = isset($errors['project_id']) ? "form__input--error" : ""; ?>

            <?php foreach ($projects as $project): ?>
            <select class="form__input form__input--select <?= $classname; ?>" name="project_id" id="project">
              <option value="<?= $project['id'] ?>"
              <?php if ($project['id'] === get_post_value('project_id')): ?>selected<?php endif; ?>><?= $project['name']; ?></option>
            </select>
            <?php endforeach; ?>
            <?php if (isset($errors['project_id'])): ?><p class="form__message"><?= $errors['project_id']; ?></p><?php endif; ?>
          </div>

          <div class="form__row">
            <?php $classname = isset($errors['dt_deadline']) ? "form__input--error" : ""; ?>

            <label class="form__label" for="date">Дата выполнения</label>

            <input class="form__input form__input--date <?= $classname; ?>" type="text" name="dt_deadline" id="date" value="<?= get_post_value('dt_deadline'); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <?php if (isset($errors['dt_deadline'])): ?><p class="form__message"><?= $errors['dt_deadline']; ?></p><?php endif; ?>
          </div>

          <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
              <input class="visually-hidden" type="file" name="file" id="file" value="<?= get_post_value('file'); ?>">

              <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
              </label>
            </div>
            <?php if (isset($errors['file'])): ?><p class="form__message"><?= $errors['file']; ?></p><?php endif; ?>
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
          </div>
        </form>
      </main>
    </div>
  </div>
</div>
