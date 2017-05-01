<main class="content__main">
                <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="index.php" method="post">
                    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <div class="radio-button-group">
                        <nav class="tasks-switch">
                          <a href="
                          <?php print("Все задачи: " . $_GET[0]) ?>" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                          <a href="<?php print("Повестка дня: " . $_GET[1]) ?>"" class="tasks-switch__item">Повестка дня</a>
                          <a href="<?php print("Завтра: " . $_GET[2]) ?>"" class="tasks-switch__item">Завтра</a>
                          <a href="<?php print("Просроченные: " . $_GET[3]) ?>"" class="tasks-switch__item">Просроченные</a>
                        </nav>
                    </div>

                    <label class="checkbox">
                        <input id="show-complete-tasks" class="checkbox__input visually-hidden" type="checkbox" checked>
                        <span class="checkbox__text">Показывать выполненные</span>
                    </label>
                </div>


                <table class="tasks">
                    <?php foreach ($array["tasks"] as $key => $val): ?>
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
                                <?=$val["task"];?>
                                </span>
                            </label>
                        </td>

                        <td class="task__date">
                        <?=$val["date"];?>
                        </td>


                        <td class="task__controls">
                            <button class="expand-control" type="button" name="button">
                            <?=$val["task"];?>
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
                        <?php endforeach; ?>
                    </tr>
                </table>
            </main>
