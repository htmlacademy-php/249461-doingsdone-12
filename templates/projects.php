<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($projects as $val) : ?>
                <?php $countTask = count_tasks($all_tasks, $val['id']);?>
                <li class="main-navigation__list-item <?php if($_GET['cat_id'] === $val['id']) echo "main-navigation__list-item--active"; ?>">
                    <a class="main-navigation__list-item-link" href="index.php?cat_id=<?=$val['id']; ?>"><?=protection_xss($val['project_name']);?></a>
                    <span class="main-navigation__list-item-count"><?=$countTask;?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button" href="add-project.php">Добавить проект</a>
</section>
