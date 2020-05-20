<?=$projects_list;?>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="get" autocomplete="off">
        <input class="search-form__input" type="text" name="search" value="<?= getGetVal('search'); ?>" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <?php if (isset($_GET['date_list'])) {
                $active_tab = $_GET['date_list'];
            }?>
            <a href="/" class="tasks-switch__item <?php if ($active_tab === '' || !isset($_GET['date_list'])) echo "tasks-switch__item--active"; ?>">Все задачи</a>
            <a href="index.php?date_list=today" class="tasks-switch__item  <?php if ($active_tab === 'today') echo "tasks-switch__item--active"; ?>">Повестка дня</a>
            <a href="index.php?date_list=tomorrow" class="tasks-switch__item  <?php if ($active_tab === 'tomorrow') echo "tasks-switch__item--active"; ?>">Завтра</a>
            <a href="index.php?date_list=expired" class="tasks-switch__item  <?php if ($active_tab === 'expired') echo "tasks-switch__item--active"; ?>">Просроченные</a>
        </nav>

        <label class="checkbox">
            <input class="checkbox__input visually-hidden show_completed" <?php if($show_complete_tasks === 1) echo "CHECKED"; ?> type="checkbox">
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
        <?php if (isset($_GET['cat_id']) && $is_cat_id === false) : ?>
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
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?= $val['id'] ?>" <?php if ($val['status'] == 1) echo "checked"; ?>>
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
    </table>
</main>
