<div class="left_menu">
    <b>Услуги по классу:</b>
    <?php
    $categories = \App\Models\service_categories
                  ::where('type_id', \App\Models\service_categories_types
                                     ::where('name', 'По классу')->first()->id)->get()->sortBy('name');
    if (count($categories) == 0){
        echo 'Нет категорий<br>';
        if (\App\Functions::is_admin()){
            echo '<ul><li><a href="#" class="btn-link btn-cat-new" type="button" data-toggle="modal" data-target="#CatNewModal"><img src="/images/icons/cat-new.svg">&nbsp;&nbsp;Создать новую</a></li></ul>';
        }
    } else {
        \App\Functions::echo_menu_list($categories);
    }
    ?>
</div>
