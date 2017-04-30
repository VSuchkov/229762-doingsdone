
<?php
$categories = ["Все", "Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];
$tasks = [
          [
            "task" => "Собеседование в IT компании",
            "date" => "01.06.2017",
            "categories" => "Работа",
            "done" => 0
          ],
          [
            "task" => "Выполнить тестовое задание",
            "date" => "25.05.2017",
            "categories" => "Работа",
            "done" => 0
          ],
          [
            "task" => "Сделать задание первого раздела",
            "date" => "21.04.2017",
            "categories" => "Учеба",
            "done" => 1
          ],
          [
            "task" => "Встреча с другом",
            "date" => "22.04.2017",
            "categories" => "Входящие",
            "done" => 0
          ],
          [
            "task" => "Купить корм для кота",
            "date" => "нет",
            "categories" => "Домашние дела",
            "done" => 0
          ],
          [
            "task" => "Заказать пиццу",
            "date" => "нет",
            "categories" => "Домашние дела",
            "done" => 0
          ]
];
require_once('./functions.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Дела в Порядке!</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body><!--class="overlay"-->
<h1 class="visually-hidden">Дела в порядке</h1>

<div class="page-wrapper">
    <div class="container container--with-sidebar">
    <?=includeTemplate('./templates/header.php', []); ?>


        <div class="content">
            <section class="content__side">
                <h2 class="content__side-heading">Проекты</h2>

                <nav class="main-navigation">
                    <ul class="main-navigation__list">
                       <?php foreach ($categories as $key => $val) : ?>
                        <li class="main-navigation__list-item
                        <?php
                        if ($key == 0) {
                        print ("main-navigation__list-item--active");
                        }
                        ?>
                        ">
                            <a class="main-navigation__list-item-link" href="#"> <?=$val;?> </a>
                            <span class="main-navigation__list-item-count"><?= calculateTasks($tasks, $val);?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <a class="button button--transparent button--plus content__side-button" href="#">Добавить проект</a>
            </section>
            <?=includeTemplate('./templates/main.php', ["categories" => $categories, "tasks" => $tasks]); ?>

        </div>
    </div>
</div>


<?=includeTemplate('./templates/footer.php', []); ?>
<div class="modal" hidden>
    <button class="modal__close" type="button" name="button">Закрыть</button>

    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form" class="" action="index.html" method="post">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input" type="text" name="name" id="name" value="" placeholder="Введите название">
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select" name="project" id="project">
                <option value="">Входящие</option>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения <sup>*</sup></label>

            <input class="form__input form__input--date" type="text" name="date" id="date" value="" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
        </div>

        <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="preview" id="preview" value="">

                <label class="button button--transparent" for="preview">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</div>

<script type="text/javascript" src="js/script.js"></script>
</body>
</html>
