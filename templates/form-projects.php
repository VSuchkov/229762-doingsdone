<div class="modal">
    <button class="modal__close" type="button" name="button">Закрыть</button>

    <h2 class="modal__heading">Добавить проект</h2>

    <form class="form" action="index.php" method="post" enctype="multipart/form-data" >
      <div class="form__row">
        <label class="form__label" for="new_project">Название <sup>*</sup></label>

        <input class="form__input
        <?php
            if (isset($array["cat_formerror"])) {
                print ("form__input--error");
            }
        ?>
        " type="text" name="new_project" id="new_project" value="" placeholder="Введите название">
        <?php
            if (isset($array["cat_formerror"]["project_busy"])) {
                print ('<span class="form__error">Категория уже существует</span>');
            }
        ?>
        <?php
            if (isset($array["cat_formerror"]["project"])) {
                print ('<span class="form__error">Заполните это поле</span>');
            }
        ?>
      </div>







      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="new_project_btn" value="Добавить">
      </div>
    </form>
  </div>
