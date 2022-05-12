<section class="content__side">
    <?php if (empty($_SESSION['user'])): ?>

        <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

        <a class="button button--transparent content__side-button" href="auth.php">Войти</a>

    <?php else: ?>

        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
            <?php foreach ($projects as $project): ?>
                <li class="main-navigation__list-item <?php if ($project['id'] === $project_id): ?>main-navigation__list-item--active<?php endif; ?>">
                    <a class="main-navigation__list-item-link" href="/?project_id=<?= $project['id']; ?>"><?= htmlspecialchars($project['name']) ?></a>
                    <span class="main-navigation__list-item-count"><?= $project['count']; ?></span>
                </li>
            <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button"
        href="new-project.php" target="project_add">Добавить проект</a>

    <?php endif; ?>
</section>

<main class="content__main"><?= $content; ?></main>
