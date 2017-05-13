<?php
session_start();
require_once("./userdata.php");
require_once('./functions.php');
/*для cookies*/
$show_complited = false;
if (isset($_GET["show_complited"])) {
    $show_complited = $_GET['show_completed'];
    setcookie("show_complited", $show_complited, strtotime("+30 days"));
} elseif (isset($_COOKIE["show_completed"])) {
    $show_completed = $_COOKIE["show_completed"];
}
/*
if ((isset($_COOKIE["show_complited"])) && ($show_complited == 1)) {
    $show_complited = 1;
    includeTemplate('./templates/main.php', ["show_complited" => $show_complited]);
}
setcookie("show_complited", $show_complited, strtotime("+30 days"));


if show_complited() {
    setcookie("show_complited", $show_complited, strtotime("+30 days"));
}
*/

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
$data = [];/*создаем пустой массив для новой задачи*/
$formerror = [];/*массив для ошибок формы задач*/
$showmodal = false;
/*подключаем форму*/

if (isset($_GET["add"])) {
    $showmodal = true;
    includeTemplate('./templates/form.php', ["categories" => $categories, "showmodal" => $showmodal]);
}
if (isset($_POST["newtask"])) {
    $data += ["done" => 0]; /*добавляем сразу ключ-значение выполнения задачи*/
    $data += ["task" => htmlspecialchars($_POST["task"])];/*экранируем название задачи*/
    $data += ["date" => htmlspecialchars($_POST["date"])];/*экранируем дату*/
    $data += ["categories" => htmlspecialchars($_POST["categories"])];/*экранируем категорию*/
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
        $showmodal = true;
        includeTemplate('./templates/form.php', ["categories" => $categories, "formerror" => $formerror, "newtask" => $data, "showmodal" => $showmodal]);
    } else {
        array_unshift($tasks, $data);
    }
    if (isset($_FILES["preview"])) {/*проверяем загружен ли файл*/
        move_uploaded_file(
            $_FILES["preview"]["tmp_name"],
            $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $_FILES["preview"]["name"]
        );/*сохраняем файл в корневой каталог*/
    }
}
if (isset($_POST["enter"])) {
    $showmodal = true;
    $data += ["email" => htmlspecialchars($_POST["email"])];
    $data += ["password" => password_hash(htmlspecialchars($_POST["password"]), PASSWORD_DEFAULT)];
    $email = $_POST["email"];
    $password = $_POST["password"];
    if ($user = searchUserByEmail($email, $users)) {
        if (password_verify($password, $user["password"])) {
            $showmodal = false;
            $_SESSION["user"] = $user;
            /*header("Location: /index.php");*/
        } else {
            $formerror += ["password" => 1];
        }
    } else {
        $formerror += ["email" => 1];
        $usererrors = count($formerror);
        includeTemplate('./templates/header.php', []);
        includeTemplate('./templates/guest.php', ["userdata" => $data, "usererror" => $formerror, "showmodal" => $showmodal]);
    }
}
if (isset($_GET["login"])) {
    $showmodal = true;
}
if ($usererrors > 0) {
    $showmodal = true;
}






/*вывод в переменный условий показа модального окна
if ((isset($_GET["add"]) || ($errors > 0)) || (isset($_GET["login"])) || (($usererrors > 0)) || ((!isset($_SESSION["user"])) && (isset($_POST["enter"])))) {
    $showmodal = 1;
}
*/
        /*array_unshift($tasks, $newtask);*/
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
        if ($showmodal == true) {
            print('class="overlay"');
        }
    ?>
    >
<h1 class="visually-hidden">Дела в порядке</h1>

<div class="page-wrapper">
    <div class="container container--with-sidebar">
    <?php
        includeTemplate('./templates/header.php', []);
        if ($_SESSION["user"]) {
            includeTemplate('./templates/main.php', ["categories" => $categories, "tasks" => $tasks, "categoryId" => $categoryId, "show_completed" => $show_completed]);
        } else {
            includeTemplate('./templates/guest.php', ["showmodal" => $showmodal]);
        }
    ?>
    </div>
</div>

<?=includeTemplate('./templates/footer.php', []); ?>


<script type="text/javascript" src="js/script.js"></script>
</body>
</html>
