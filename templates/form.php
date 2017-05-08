<div class="modal">
    <button class="modal__close" type="button" name="button">Закрыть</button>

    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form" class="" action="index.php" method="post" enctype="multipart/form-data" >
      <div class="form__row">
        <label class="form__label" for="task">Название <sup>*</sup></label>

        <input class="form__input
        <?php
            if (isset($array["formerror"]["task"])) {
                print ("form__input--error");
            }
        ?>
        " type="text" name="task" id="name" value="" placeholder="Введите название">
        <?php
            if (isset($array["formerror"]["task"])) {
                print ('<span class="form__error">Заполните это поле</span>');
            }
        ?>
      </div>

      <div class="form__row">
        <label class="form__label" for="categories">Проект <sup>*</sup></label>

        <select class="form__input
        <?php
            if (isset($array["formerror"]["categories"])) {
                print ("form__input--error");
            }
        ?>
        form__input--select" name="categories" id="project">
          <?php foreach ($array["categories"] as $key => $val): ?>
          <option value="<?=$val;?>"><?=$val;?></option>
          <?php endforeach; ?>
        </select>
        <?php
            if (isset($array["formerror"]["categories"])) {
                print ('<span class="form__error">Заполните это поле</span>');
            }
        ?>
      </div>

      <div class="form__row">
        <label class="form__label" for="date">Дата выполнения <sup>*</sup></label>

        <input class="form__input
        <?php
            if (isset($array["formerror"]["date"])) {
                print ("form__input--error");
            }
        ?>
        form__input--date" type="text" name="date" id="date" value="" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
        <?php
            if (isset($array["formerror"]["date"])) {
                print ('<span class="form__error">Заполните это поле</span>');
            }
        ?>
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
        <input class="button" type="submit" name="newtask" value="Добавить">
      </div>
    </form>
  </div>
