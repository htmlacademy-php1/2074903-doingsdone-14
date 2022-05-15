<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="GET" autocomplete="off">
    <input class="search-form__input" type="text" name="search"
        value="<?= filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS); ?>"
        placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item
        <?php if (empty($today or $tomorrow or $overdue)) : ?>
            tasks-switch__item--active
        <?php endif; ?>
            ">Все задачи</a>
        <a href="/?today=1" class="tasks-switch__item
        <?php if (!empty($today)) : ?>
            tasks-switch__item--active
        <?php endif; ?>
            ">Повестка дня</a>
        <a href="/?tomorrow=1" class="tasks-switch__item
        <?php if (!empty($tomorrow)) : ?>
            tasks-switch__item--active
        <?php endif; ?>
            ">Завтра</a>
        <a href="/?overdue=1" class="tasks-switch__item
        <?php if (!empty($overdue)) : ?>
            tasks-switch__item--active
        <?php endif; ?>
            ">Просроченные</a>
    </nav>

    <label class="checkbox">
        <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
        <input class="checkbox__input visually-hidden show_completed" type="checkbox"
        <?php if ($show_complete_tasks) : ?>
            checked
        <?php endif; ?>
            >
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
<?php foreach ($tasks as $task) : ?>
    <?php if (!$show_complete_tasks and $task['status']) : continue ?>
    <?php endif; ?>
    <tr class="tasks__item task
    <?php if ($task['status']) : ?>
        task--completed
    <?php endif; ?>
    <?php if (is_hot($task)) : ?>
        task--important
    <?php endif; ?>
        ">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox"
                    type="checkbox" value="<?= $task['id']; ?>"
                <?php if ($task['status']) : ?>
                    checked
                <?php endif; ?>>
                <span class="checkbox__text"><?= htmlspecialchars($task['name']); ?></span>
            </label>
        </td>

        <td class="task__file">
            <?php if (!empty($task['file'])) : ?>
            <a class="download-link" href="#"><?= $task['file'] ?></a>
            <?php endif; ?>
        </td>


        <td class="task__date">
        <?php if (!empty($task['dt_deadline'])) : ?>
            <?= $task['dt_deadline']; ?>
        <?php endif; ?>
        </td>

    </tr>
<?php endforeach; ?>
</table>
