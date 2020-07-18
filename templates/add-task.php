<?=$projects_list;?>

<main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>
    <form class="form"  action="add.php" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>
            <?php $classname = isset($errors['task_name']) ? "form__input--error" : ""; ?>
            <?php if (isset($errors['task_name'])) : ?>
                <p class="form__message"><?= $errors['task_name'] ?></p>
            <?php endif; ?>
            <input class="form__input <?= $classname;?>" type="text" name="task_name" id="name" value="<?= getPostVal('task_name'); ?>" placeholder="Введите название">
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>
            <?php $classname = isset($errors['project_id']) ? "form__input--error" : ""; ?>
            <?php if (isset($errors['project_id'])) : ?>
                <p class="form__message"><?= $errors['project_id'] ?></p>
            <?php endif; ?>
            <select class="form__input form__input--select <?= $classname;?>" name="project_id" id="project">
                <option value="">Выбери проект</option>
                <?php foreach ($projects as $val) : ?>
                    <option value="<?=$val['id']; ?>" <?php if ($val['id'] === getPostVal('project_id')) : ?>selected<?php endif; ?>><?=protection_xss($val['project_name']);?></option>
                <?php endforeach;?>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>
            <?php $classname = isset($errors['run_to']) ? "form__input--error" : ""; ?>
            <?php if (isset($errors['run_to'])) : ?>
                <p class="form__message"><?= $errors['run_to'] ?></p>
            <?php endif; ?>
            <input class="form__input form__input--date <?= $classname;?>" type="text" name="run_to" id="date" value="<?= getPostVal('run_to'); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
        </div>

        <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="file" id="file" value="">

                <label class="button button--transparent" for="file">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
