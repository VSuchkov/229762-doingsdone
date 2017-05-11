<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <link rel="stylesheet" href="../css/normalize.css">
  <link rel="stylesheet" href="../css/style.css">
</head>

<body class="body-background
<?php
    if ((isset($_GET["login"])) || ((count($array["usererror"]) > 0)) || ((!isset($_SESSION["user"])) && (isset($_POST["enter"])))) {
    print("overlay");
  }
?>
">
  <!--class="overlay"-->
  <h1 class="visually-hidden">Дела в порядке</h1>




      <div class="content">
        <section class="welcome">
          <h2 class="welcome__heading">«Дела в порядке»</h2>

          <div class="welcome__text">
            <p>«Дела в порядке» — это веб приложение для удобного ведения списка дел. Сервис помогает пользователям не забывать о предстоящих важных событиях и задачах.</p>

            <p>После создания аккаунта, пользователь может начать вносить свои дела, деля их по проектам и указывая сроки.</p>
          </div>

          <a class="welcome__button button" href="#">Зарегистрироваться</a>
        </section>
      </div>




  <div class="modal"
  <?php
    if ((isset($_GET["login"])) || (isset($_POST["enter"]))) {
      print ("");
    } else {/*проверка пустой ли $_GET["login"]*/
      print ("hidden");/*если параметра запроса login нет, то добавляется hidden*/
    }
  ?>

  >
    <button class="modal__close" type="button" name="button">Закрыть</button>

    <h2 class="modal__heading">Вход на сайт</h2>

    <form class="form" class="" action="index.php" method="post">
      <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>

        <input class="form__input
        <?php
            if (isset($array["usererror"]["email"])) {
                print ("form__input--error");
            }
        ?>
        " type="text" name="email" id="email"
        <?php
            if (isset($_POST["email"])) {
                print('value="' . $_POST["email"] . '"');
            }
        ?>



         value="" placeholder="Введите e-mail">
        <?php if (isset($array["usererror"]["email"])): ?> <!--проверяем наличие данных-->
            <p class="form__message">E-mail введён некорректно</p>
         <?php endif; ?>

      </div>

      <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>

        <input class="form__input
        <?php
            if ((isset($array["usererror"]["password"])) || ((!isset($_SESSION["user"])) && (isset($_POST["enter"])))) {
                print ("form__input--error");
            }
        ?>
        " type="password" name="password" id="password" value="" placeholder="Введите пароль">
        <?php if ((!isset($_SESSION["user"])) && (isset($_POST["enter"]))): ?> <!--проверяем наличие данных-->
            <p class="form__message">Пароль введён неверно</p>
         <?php endif; ?>
      </div>

      <div class="form__row">
        <label class="checkbox">
          <input class="checkbox__input visually-hidden" type="checkbox" checked>
          <span class="checkbox__text">Запомнить меня</span>
        </label>
      </div>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="enter" value="Войти">
      </div>
    </form>
  </div>
</body>
</html>
