<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($projects as $val) : ?>
                <?php $countTask = count_tasks($all_tasks, $val['id']);?>
                <li class="main-navigation__list-item <?php if($_GET['cat_id'] == $val['id']) echo "main-navigation__list-item--active"; ?>">
                    <a class="main-navigation__list-item-link" href="index.php?cat_id=<?=$val['id']; ?>"><?=protection_xss($val['project_name']);?></a>
                    <span class="main-navigation__list-item-count"><?=$countTask;?></span>
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
            <input class="checkbox__input visually-hidden show_completed" <?php if($show_complete_tasks === 1) echo "CHECKED"; ?> type="checkbox">
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
        <?php if (isset($_GET['cat_id']) && $is_cat_id == false) : ?>
            <?php http_response_code(404); ?>
            <?php http_response_code(); ?>
            <?php $error = http_response_code(); ?>
            <?php print("Запрашиваемая категория не найдена. Error: " . $error); ?>
            <?php else: ?>
        <?php foreach ($tasks as $key => $val) : ?>
            <?php if ($show_complete_tasks === 0 && $val['status'] == 1) {
                continue;
            } ?>
            <tr class="tasks__item task <?php if ($val['status'] == 1) echo "task--completed"; ?> <?php if (timeleft($val['run_to']) <= 24 && $val['status'] == 0) echo "task--important"; ?> ">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
                        <span class="checkbox__text"><?= protection_xss($val['task_name']); ?></span>
                    </label>
                </td>

                <td class="task__date"><?= $val['run_to'] ?></td>

                <td class="task__date"></td>
            </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        <!--показывать следующий тег <tr/>, если переменная $show_complete_tasks равна единице-->
    </table>
</main>
