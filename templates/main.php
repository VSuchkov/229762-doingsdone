<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <li class="main-navigation__list-item
                <?php
                    if ($array["categoryId"] == 0) {
                        print ("main-navigation__list-item--active");
                    }
                ?>
                ">
                    <a class="main-navigation__list-item-link" href="./index.php?categoryId=0"> Все </a>
                    <span class="main-navigation__list-item-count"> <?= count($array["tasks"]);?> </span>
                </li>
            <?php foreach ($array['categories'] as $key => $val): ?>

                <li class="main-navigation__list-item
                <?php
                    if ($val['id'] == $array["categoryId"]) {
                        print ("main-navigation__list-item--active");
                    }
                ?>
                ">
                    <a class="main-navigation__list-item-link" href="./index.php?categories=<?=$val['id'];?>"> <?=htmlspecialchars($val['name']);?> </a>
                    <span class="main-navigation__list-item-count"> <?= calculateTasks($array["tasks"], $val['id']);?> </span>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button" href="index.php?new_project=1">Добавить проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="index.php" method="post">
            <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">

            <input class="search-form__submit" type="submit" name="search_btn" value="Искать">
            <?php
                if ($array["search_error"] == true) {
                    print ('<p class="form__message">Пустой запрос</p>');
                }
            ?>
        </form>

        <div class="tasks-controls">
            <div class="radio-button-group">
                <nav class="tasks-switch">
                    <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                    <a href="/" class="tasks-switch__item">Повестка дня</a>
                    <a href="/" class="tasks-switch__item">Завтра</a>
                    <a href="/" class="tasks-switch__item">Просроченные</a>
                </nav>
            </div>

            <label class="checkbox">
                <input id="show-complete-tasks" class="checkbox__input visually-hidden" type="checkbox"
                <?php
                    if ($array["show_completed"]) {
                        print (" checked");
                    }
                ?>
                >
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>


        <table class="tasks">
            <?php foreach ($array["tasks"] as $key => $val): ?>
                <?php if (($array["categoryId"] == $val["project_id"]) || ($array["categoryId"] == 0)): ?>
                <tr class="tasks__item task
                <?php
                    if ($val["done"] == 1) {
                        print("task--completed");
                    }
                ?>
                <?php
                    if (($array["show_completed"]) && ($val["done"] == 1)) {
                        print ("");
                    } elseif ($val["done"] == 1) {
                        print(" hidden");
                    }
                ?>
                ">

                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden" type="checkbox">
                            <span class="checkbox__text">
                            <?=htmlspecialchars($val["task"]);?>
                            </span>
                        </label>
                    </td>

                    <td class="task__date">
                    <?=htmlspecialchars($val["date_done"]);?>
                    </td>


                    <td class="task__controls">
                        <button class="expand-control" type="button" name="button">
                        <?=htmlspecialchars($val["task"]);?>
                        </button>

                        <ul class="expand-list hidden">
                            <li class="expand-list__item">
                                <a href="/index.php?action='done'&task_id=
                                <?php
                                    print $val["id"];
                                ?>
                                ">Выполнить</a>
                            </li>

                            <li class="expand-list__item">
                                <a href="#">Удалить</a>
                            </li>

                            <li class="expand-list__item">
                                <a href="#">Дублировать</a>
                            </li>
                        </ul>
                    </td>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tr>
        </table>
    </main>
</div>
