<?php
$categories = ["Все",
                "Входящие",
                "Учеба",
                "Работа",
                "Домашние дела",
                "Авто"];
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

require_once("./userdata.php");
require_once('./functions.php');
/*проверяем существование переменной*/
if (isset($_GET["categories"])) {
/*получаем номер категории и проверяем её наличие*/
    if (isset($categories[$_GET["categories"]])) {
/*условие для показа задач для проекта*/
        $categoryId = $_GET["categories"];
/*условие для возврата строки 404*/
    } else {
        return header("HTTP/1.1 404 Not Found");
    }
/*условие показывать все задачи(соответствует $categories[0])*/
} else {
       $categoryId = 0;
}

$newtask = [];/*создаем пустой массив для новой задачи*/
$formerror = [];/*массив для ошибок формы задач*/
$userdata = [];/*массив для даных пользователя*/
$usererror = [];/*массив для ошибок формы пользователя*/
/*подключаем форму*/
if (isset($_GET["add"])) {
    includeTemplate('./templates/form.php', ["categories" => $categories,]);
}
if (isset($_POST["newtask"])) {
    $newtask += ["done" => 0]; /*добавляем сразу ключ-значение выполнения задачи*/
    $newtask += ["task" => htmlspecialchars($_POST["task"])];/*экранируем название задачи*/
    $newtask += ["date" => htmlspecialchars($_POST["date"])];/*экранируем дату*/
    $newtask += ["categories" => htmlspecialchars($_POST["categories"])];/*экранируем категорию*/
    if ($_POST["task"] == "") {
        $formerror += ["task" => 1]; /*добавляем о том что ошибка истинна*/
    }
    if ($_POST["date"] == "") {
        $formerror += ["date" => 1]; /*добавляем о том что ошибка истинна*/
    }
    if ($_POST["categories"] == "") {
        $formerror += ["categories" => 1]; /*добавляем о том что ошибка истинна*/
    }
    $errors = count($formerror);/*переменная количества ошибок формы*/
    if ($errors > 0) { /*считаем количество ошибок*/
        includeTemplate('./templates/form.php', ["categories" => $categories, "formerror" => $formerror, "newtask" => $newtask]);
    } else {
        array_unshift($tasks, $newtask);
    }
    if (isset($_FILES["preview"])) {/*проверяем загружен ли файл*/
        move_uploaded_file(
            $_FILES["preview"]["tmp_name"],
            $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $_FILES["preview"]["name"]
        );/*сохраняем файл в корневой каталог*/
    }
}
session_start();
if (isset($_GET["login"])) {
    includeTemplate('./templates/guest.php', []);
}

if (isset($_POST["enter"])) {
    $userdata += ["email" => htmlspecialchars($_POST["email"])];
    $userdata += ["password" => password_hash(htmlspecialchars($_POST["password"]), PASSWORD_DEFAULT)];/*получаем отпечаток*/
    if ($_POST["email"] == "") {
        $usererror += ["email" => 1]; /*добавляем о том поле не заполнено*/
    }
    if ($_POST["password"] == "") {
        $usererror += ["password" => 1]; /*добавляем о том что поле не заполнено*/
    }
    $usererrors = count($usererror);
    if ($usererrors > 0) { /*считаем количество ошибок*/
        includeTemplate('./templates/guest.php', ["userdata" => $userdata, "usererror" => $usererror]);
    } else {

        if (!empty($_POST["enter"])) {/*проверяем была ли отправлена форма*/
            $email = $_POST["email"];
            $password = $_POST["password"];
            if ($user = searchUserByEmail($email, $users)) {
                if (password_verify($password, $user["password"])) {
                    $_SESSION["user"] = $user;

                    header("Location: /index.php");
                } else {
                    includeTemplate('./templates/guest.php', ["user" => $user, "password" => $password]);
                }
            }
        }
        /*array_unshift($tasks, $newtask);*/
    }
}

/*
if (isset($_GET["login"])) {
    includeTemplate('./templates/guest.php', ["userdata" => $userdata,]);
}
*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Дела в Порядке!</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body
    <?php
            if (isset($_GET["add"]) || ($errors > 0)) {
            print('class="overlay"');
        }
    ?>
    >
<h1 class="visually-hidden">Дела в порядке</h1>

<div class="page-wrapper">
    <div class="container container--with-sidebar">
    <?=includeTemplate('./templates/header.php', []); ?>
    <?=includeTemplate('./templates/main.php', ["categories" => $categories, "tasks" => $tasks, "categoryId" => $categoryId]); ?>
    </div>
</div>

<?=includeTemplate('./templates/footer.php', []); ?>


<script type="text/javascript" src="js/script.js"></script>
</body>
</html>
