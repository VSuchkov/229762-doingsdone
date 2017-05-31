<?php
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    session_start();
    require_once('./functions.php');
    require_once('./vendor/autoload.php');
    /*чекбокс*/
    $show_completed = 0;
    if (isset($_GET["show_completed"])) {
        $show_completed = $_GET['show_completed'];
        setcookie("show_completed", $show_completed, strtotime("+30 days"));
    } elseif (isset($_COOKIE["show_completed"])) {
        $show_completed = $_COOKIE["show_completed"];
    }
/*
    $transport = Swift_SmtpTransport::newInstance('smtp.example.org', 25);
    $message = Swift_Message::newInstance();
    $message->setTo(["doingsdone@mail.ru" => "Дела в порядке"]);
    $message->setSubject("Уведомление от сервиса «Дела в порядке»");
    $message->setBody("Уважаемый, $_SESSION["user"]["name"]. У вас запланирована задача <имя задачи> на <время задачи>");
    $message->setFrom("doingsdone@mail.ru", "Doingsdone");
    // Отправка сообщения
    $mailer = Swift_Mailer::newInstance($transport);
    $mailer->send($message);
*/
    $con = mysqli_connect("localhost", "root", "", "doingsdone");
    if (!$con) {
        return header("HTTP/1.1 500");

    }
    if  (isset($_SESSION["user"])) {
        $user_id = $_SESSION["user"]["id"];
        $sql = "SELECT id, name, user_id FROM projects WHERE user_id = ?";
        $categories = get_data($con, $sql, [$user_id]);
        $categoryId = "";
        $task_data = [$user_id, $show_completed];
        $additional_where = '';

    if (isset($_GET["categories"])) {
        $categoryId = $_GET["categories"];
        $sql = "SELECT name, user_id FROM projects WHERE id = ?";
        $category = get_data($con, $sql, [$categoryId]);


            if($category) {
                    $additional_where = 'AND project_id = ?'; //Добавляем в запрос нужный параметр
                    $task_data[] = $categoryId; //Добавляем в массив параметров параметр категории
            } else {
                return header("HTTP/1.1 404 Not Found");
            }

        }
        $sql = "SELECT * FROM tasks WHERE user_id = ? AND done =? $additional_where ORDER BY date_done DESC";
        $tasks = get_data($con, $sql, $task_data);
    }







    $formerror = [];/*массив для ошибок формы задач*/
    $showmodal = false;

    /*подключаем форму*/
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
        $date_done = date('Y.m.d H:i', strtotime(htmlspecialchars($_POST["date"])));

        $task_data += ["date_done" => $date_done];
        $task_data += ["done" => 0]; /*добавляем сразу ключ-значение выполнения задачи*/
        if ($date_done < date("Y.m.d H:i")) {
            $formerror += ["date_old" => 1];
        }
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
            );
            $sql = " SELECT * FROM tasks WHERE task = ?";
            $loading_file = include_data($con, $sql, [$task_data["task"]]);/*сохраняем файл в корневой каталог*/
        }
    }
    /*помечаем как выполненное*/
    if (isset($_GET["action"])) {
        $task_id = $_GET["task_id"];
        $sql = "SELECT * FROM tasks WHERE id = ?";
        $task_done = get_data($con, $sql, [$task_id]);
        /*var_dump($task_done);*/
        if ($task_done[0]["done"] == 0) {
            $done_task = update_data($con, "tasks", ["done" => 1], ["id" => $task_id]);
            if ($done_task) {
             header("Location: /index.php");
            }
        }
    }
    /*добавлении категории*/
    if (isset($_GET["new_project"])) {
        $showmodal = true;
        includeTemplate('./templates/form-projects.php', []);
    }
    if (isset($_POST["new_project_btn"])) {
        $cat_formerror = [];
        $project_data = [];
        $user_id = $_SESSION["user"]["id"];
        $new_project = $_POST["new_project"];
        $project_data += ["new_project" => $new_project];
        $project_data += ["user_id" => $user_id];
        $sql = "SELECT name FROM projects WHERE name = ?";
        $project = get_data($con, $sql, [$new_project]);
        if (!empty($project)) {
            $cat_formerror += ["project_busy" => 1];
        }
        if ($new_project == "") {
            $cat_formerror += ["project" => 1];
        }
        $errors = count($cat_formerror);
        if ($errors > 0) { /*считаем количество ошибок*/
            $showmodal = true;
            includeTemplate('./templates/form-projects.php', ["categories" => $categories, "cat_formerror" => $cat_formerror, "project" => $project, "showmodal" => $showmodal]);
        } else {
            $sql = "INSERT INTO projects (name, user_id) VALUES (?, ?)";
            $res = include_data($con, $sql, $project_data);
            if ($res) {
                header("Location: /index.php");
            } else {
                print "error";
            }
        }
    }
    /*поиск*/
    $search_error = false;
    if (isset($_POST["search_btn"])) {
        $search_text = trim($_POST["search"]);
        if ($search_text == "") {
            $search_error = true;
        } else {
            $search_text = "%$search_text%";
            $user_id = $_SESSION["user"]["id"];
            $sql = " SELECT * FROM tasks WHERE user_id = ? AND task LIKE ? ORDER BY date_done DESC; ";
            $tasks = get_data($con, $sql, [$user_id, $search_text]);
        }
    }
    /*регистрация*/
    $user_add = false;
    if (isset($_GET["reg"])) {
        includeTemplate('./templates/register.php', []);
    }
    if (isset($_POST["registration"])) {
        $reg_formerror = [];
        $post_mail = $_POST["email"];
        $sql = "SELECT email FROM users WHERE email = ?";
        $emails = get_data($con, $sql, [$post_mail]);
        if (!empty($emails)) {
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
            includeTemplate('./templates/register.php', ["reg_formerror" => $reg_formerror]);
        } else {
            $new_user = [];
            $new_user += ["email" => htmlspecialchars($_POST["email"])];
            $new_user += ["login" => htmlspecialchars($_POST["name"])];
            $new_user += ["password" => password_hash(htmlspecialchars($_POST["password"]), PASSWORD_DEFAULT)];
            $sql = "INSERT INTO users (email, login, password) VALUES (?, ?, ?)";
            $res = include_data($con, $sql, $new_user);
            if ($res) {
                $new_user += ["id" => $res];
                $user_add += true;
                $showmodal = true;
            } else {
                print "error";
            }
        }
    }

    /*вход на сайт*/
    if (isset($_POST["enter"])) {
        $user_data = [];
        $showmodal = true;
        $user_data += ["email" => htmlspecialchars($_POST["email"])];
        $user_data += ["password" => password_hash(htmlspecialchars($_POST["password"]), PASSWORD_DEFAULT)];
        $email = $_POST["email"];
        $password = $_POST["password"];
        if ($user = searchUser($con, $email, $password)) {
            if (password_verify($password, $user["password"])) {
                $showmodal = false;
                $_SESSION["user"] = $user;
                header("Location: /index.php");
            } else {
                $formerror += ["password" => 1];
            }
        } else {
            $user_add = false;
            $formerror += ["email" => 1];
            $usererrors = count($formerror);
            includeTemplate('./templates/header.php', []);
            includeTemplate('./templates/guest.php', ["userdata" => $user_data, "usererror" => $formerror, "showmodal" => $showmodal, "user_add" => $user_add]);
            if ($usererrors > 0) {
                $showmodal = true;
            }
        }
    }

    if (isset($_GET["login"])) {
        $showmodal = true;
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
                includeTemplate('./templates/main.php', ["categories" => $categories, "tasks" => $tasks, "categoryId" => $categoryId, "show_completed" => $show_completed, "search_error" => $search_error]);
            } else {
                includeTemplate('./templates/guest.php', ["showmodal" => $showmodal, "user_add" => $user_add]);
            }
        ?>
        </div>
    </div>

    <?=includeTemplate('./templates/footer.php', []); ?>


    <script type="text/javascript" src="js/script.js"></script>
    </body>
    </html>
