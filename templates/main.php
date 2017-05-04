<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
               <?php foreach ($array['categories'] as $key => $val): ?>
                <li class="main-navigation__list-item
                <?php
                if ($key == 0) {
                print ("main-navigation__list-item--active");
                }
                ?>
                ">
                    <a class="main-navigation__list-item-link" href="./index.php?categories=<?php print htmlspecialchars($key); ?>"> <?=htmlspecialchars($val);?> </a>
                    <span class="main-navigation__list-item-count"> <?= calculateTasks($array["tasks"], $val);?> </span>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button" href="#">Добавить проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="index.php" method="post">
            <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

            <input class="search-form__submit" type="submit" name="" value="Искать">
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
                <input id="show-complete-tasks" class="checkbox__input visually-hidden" type="checkbox" checked>
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>


        <table class="tasks">
            <?php foreach ($array["tasks"] as $key => $val): ?>
            <?php if ($array["categories"][$array["categoryId"]] == $val['tasks']): ?>
            <tr class="tasks__item task
            <?php
                if ($val["done"] == 1) {
                    print("task--completed");
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
                <?=htmlspecialchars($val["date"]);?>
                </td>


                <td class="task__controls">
                    <button class="expand-control" type="button" name="button">
                    <?=htmlspecialchars($val["task"]);?>
                    </button>

                    <ul class="expand-list hidden">
                        <li class="expand-list__item">
                            <a href="#">Выполнить</a>
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
