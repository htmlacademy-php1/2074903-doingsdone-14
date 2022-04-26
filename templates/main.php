<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
        <?php foreach ($projects as $p): ?>
            <li class="main-navigation__list-item">
                <a class="main-navigation__list-item-link" href="#"><?= $p['name'] ?></a>
                <span class="main-navigation__list-item-count"><?= $p['count'] ?></span>
            </li>
        <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button"
       href="pages/form-project.html" target="project_add">Добавить проект</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="post" autocomplete="off">
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
            <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden show_completed" type="checkbox"
            <?php if ($show_complete_tasks): ?> checked <?php endif; ?> >
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
    <?php foreach ($tasks as $t): ?>
        <?php if (!$show_complete_tasks and $t['status']): continue ?><?php endif; ?>
        <tr class="tasks__item task <?php if ($t['status']): ?>task--completed<?php endif; ?> <?php if (is_hot($t)): ?>task--important<?php endif; ?>">
            <td class="task__select">
                <label class="checkbox task__checkbox">
                    <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
                    <span class="checkbox__text"><?= $t['name']; ?></span>
                </label>
            </td>

            <td class="task__file">
                <?php if (isset($t['file'])): ?>
                <a class="download-link" href="#"><?= $t['file'] ?></a>
                <?php endif; ?>
            </td>

            <?php if (isset($t['dt_deadline'])): ?>
            <td class="task__date"><?= $t['dt_deadline']; ?></td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </table>
</main>
