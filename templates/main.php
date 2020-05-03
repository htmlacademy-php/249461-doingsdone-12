<?=$projects_list;?>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="get" autocomplete="off">
        <input class="search-form__input" type="text" name="search" value="<?= getGetVal('search'); ?>" placeholder="Поиск по задачам">

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
            <?php elseif (isset($_GET['search']) && empty($tasks) ) : ?>
            <?php print("По вашему запросу ничего не найдено"); ?>
            <?php else: ?>
        <?php foreach ($tasks as $key => $val) : ?>
            <?php if ($show_complete_tasks === 0 && $val['status'] == 1) {
                continue;
            } ?>
            <tr class="tasks__item task <?php if ($val['status'] == 1) echo "task--completed"; ?> <?php if (timeleft($val['run_to']) <= 24 && $val['status'] == 0) echo "task--important"; ?> ">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?= $val['id'] ?>">
                        <span class="checkbox__text"><?= protection_xss($val['task_name']); ?></span>
                    </label>
                </td>


                <td class="task__file">
                    <?php if ($val['file_name']) : ?>
                        <a class="download-link" href="<?= $val['file_path'] ?>" download=""><?= $val['file_name'] ?></a>
                    <? endif; ?>
                </td>


                <td class="task__date"><?= $val['run_to'] ?></td>

            </tr>

        <?php endforeach; ?>
        <?php endif; ?>
        <!--показывать следующий тег <tr/>, если переменная $show_complete_tasks равна единице-->
    </table>
</main>
