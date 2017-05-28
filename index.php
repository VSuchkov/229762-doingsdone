<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();
/*require_once("./userdata.php");*/
require_once('./functions.php');

$con = mysqli_connect("localhost", "root", "", "doingsdone");
if (!$con) {
    print "error";
} else {
    $sql = "SELECT id, name FROM projects";
    $categories = get_data($con, $sql, []);
    /*var_dump($categories);*/
    $sql = "SELECT id, email, login, password FROM users";
    $users = get_data($con, $sql, []);
    /*var_dump($users);*/
    if (isset($_SESSION["user"])) {
        $user_id = $_SESSION["user"]["id"];
        $sql = "SELECT id, project_id, user_id, task, date_done, done FROM tasks WHERE user_id = ?";
        $tasks = get_data($con, $sql, [$user_id]);

    }
   /* $sql = "SELECT id, project_id, user_id, task, date_done, done FROM tasks";
    $tasks = get_data($con, $sql, []); */
    /*var_dump($tasks);*/
}

/*проверяем существование переменной*/
if (isset($_GET["categories"])) {
/*получаем номер категории и проверяем её наличие*/
    $sql = "SELECT id, name FROM projects";
    $categories = get_data($con, $sql, []);
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




$formerror = [];/*массив для ошибок формы задач*/
$showmodal = false;
/*подключаем форму*/
if (isset($_GET["new_project"])) {

}

if (isset($_GET["add"])) {
    $showmodal = true;
    includeTemplate('./templates/form.php', ["categories" => $categories, "showmodal" => $showmodal]);
}

if (isset($_POST["newtask"])) {
    $formerror = [];
    $task_data = [];
    $task_data += ["project_id" => $_POST["categories"]];
    $task_data += ["user_id" => $_SESSION["user"]["id"]];
    $task_data += ["task" => htmlspecialchars($_POST["task"])];/*экранируем название задачи*/
    $task_data += ["date_done" => @date('Y.m.d', strtotime(htmlspecialchars($_POST["date"])))];/*экранируем дату*/
    $task_data += ["done" => 0]; /*добавляем сразу ключ-значение выполнения задачи*/
    if ($_POST["categories"] == "") {
        $formerror += ["categories" => 1]; /*добавляем о том что ошибка истинна*/
    }
    if ($_POST["task"] == "") {
        $formerror += ["task" => 1]; /*добавляем о том что ошибка истинна*/
    }
    if ($_POST["date"] == "") {
        $formerror += ["date" => 1]; /*добавляем о том что ошибка истинна*/
    }


    $errors = count($formerror);
    if ($errors > 0) { /*считаем количество ошибок*/
        $showmodal = true;
        includeTemplate('./templates/form.php', ["categories" => $categories, "formerror" => $formerror, "newtask" => $task_data, "showmodal" => $showmodal]);
    } else {
        $sql = "INSERT INTO tasks (project_id, user_id, task, date_done, done) VALUES ( ?, ?, ?, ?, ?)";
        $res = include_data($con, $sql, $task_data);
        if ($res) {
            header("Location: /index.php");
        } else {
            print "error";
        }
    }
    if (isset($_FILES["preview"])) {/*проверяем загружен ли файл*/
        move_uploaded_file(
            $_FILES["preview"]["tmp_name"],
            $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $_FILES["preview"]["name"]
        );/*сохраняем файл в корневой каталог*/
    }
}

if (isset($_GET["reg"])) {
    includeTemplate('./templates/register.php', []);
}

if (isset($_POST["registration"])) {
    $sql = "SELECT email FROM users";
    $emails = get_data($con, $sql, []);
    var_dump($emails);
    $reg_formerror = [];
    $mail_busy = in_array($_POST["email"], $emails);
    if ($mail_busy) {
        $reg_formerror += ["mail_busy" => 1];

    }
    var_dump($reg_formerror);
    if ($_POST["email"] == "") {
        $reg_formerror += ["email" => 1];
    }
    if ($_POST["password"] == "") {
        $reg_formerror += ["password" => 1];
    }
    if ($_POST["name"] == "") {
        $reg_formerror += ["name " => 1];
    }
    $reg_errors = count($reg_formerror);
    if ($reg_errors > 0) {
        includeTemplate('./templates/register.php', [/*"reg_data" => $reg_data,*/ "reg_formerror" => $reg_formerror]);
    } else {
        $new_user = [];
        $new_user += ["email" => htmlspecialchars($_POST["email"])];
        $new_user += ["login" => htmlspecialchars($_POST["name"])];
        $new_user += ["password" => password_hash(htmlspecialchars($_POST["password"]), PASSWORD_DEFAULT)];
        $sql = "INSERT INTO users (email, login, password) VALUES (?, ?, ?)";
        $res = include_data($con, $sql, $new_user);
        if ($res) {
            $new_user += ["id" => $res];
            $_SESSION["user"] = $new_user;
            header("Location: /index.php");
        } else {
            print "error";
        }
    }
}


if (isset($_POST["enter"])) {
    $user_data = [];
    $showmodal = true;
    $user_data += ["email" => htmlspecialchars($_POST["email"])];
    $user_data += ["password" => password_hash(htmlspecialchars($_POST["password"]), PASSWORD_DEFAULT)];
    $email = $_POST["email"];
    $password = $_POST["password"];
    if ($user = searchUserByEmail($email, $users)) {
        if (password_verify($password, $user["password"])) {

            $showmodal = false;
            $_SESSION["user"] = $user;

            /*$sql = "SELECT id, project_id, user_id, task, date_done, done FROM tasks WHERE user_id = $user_id";
            $tasks = get_data($con, $sql, []);*/
            header("Location: /index.php");
        } else {
            $formerror += ["password" => 1];
        }
    } else {
        $formerror += ["email" => 1];
        $usererrors = count($formerror);
        includeTemplate('./templates/header.php', []);
        includeTemplate('./templates/guest.php', ["userdata" => $user_data, "usererror" => $formerror, "showmodal" => $showmodal]);
        if ($usererrors > 0) {
            $showmodal = true;
        }
    }
}



if (isset($_GET["login"])) {
    $showmodal = true;
}
$show_completed = false;
if (isset($_GET["show_completed"])) {
    $show_completed = $_GET['show_completed'];
    setcookie("show_completed", $show_completed, strtotime("+30 days"));
} elseif (isset($_COOKIE["show_completed"])) {
    $show_completed = $_COOKIE["show_completed"];
}
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
        if (isset($_SESSION["user"])) {
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
