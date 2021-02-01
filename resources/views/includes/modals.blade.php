<!-- Создание категории -->
<div class="modal fade" id="CatNewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Создать категорию</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    <b>Имя:</b><br>
                    <input type="text" name="name" id="cat-new-name" class="form-control">
                </p>
                <p>
                    <b>Родительская категория:</b><br>
                    <select class="form-control" name="parent_category_id" id="cat-new-parent-select"></select>
                </p>
                <p>
                    <b>Тип категории:</b><br>
                    <select class="form-control" name="type_id" id="cat-new-type-select">
                        <?php
                        $possible_cat_types = \App\Models\service_categories_types::all();
                        foreach ($possible_cat_types as $type){
                            echo '<option value="'.$type->id.'">'.$type->name.'</option>';
                        }
                        ?>
                    </select>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary btn-save btn-cat-new">Подтвердить</button>
            </div>
        </div>
    </div>
</div>



@if(isset($content) && $content == 'category')
<!-- Добавление категории -->
<div class="modal fade" id="CatAddModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавить существующую категорию</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><b>Выберите категорию:</b></p>
                <select class="form-control" name="cat-add-select" id="cat-add-select">
                    <?php
                    $possible_child_cats = \App\Models\service_categories::where('type_id', $cat->type_id)->get();
                    foreach ($possible_child_cats as $pcat){
                        // категория не может быть сама себе родителем и не может иметь своего родителя в качестве своего же ребенка
                        if ($cat->id != $pcat->id &&
                            ($cat->parent()->first() == null ||
                                ($cat->parent()->first() != null && $cat->parent()->first()->id != $pcat->id))){
                            echo '<option value="'.$pcat->id.'">'.$pcat->name.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary btn-save btn-cat-add">Подтвердить</button>
            </div>
        </div>
    </div>
</div>



<!-- Подтвердить удаление категории -->
<div class="modal fade" id="CatDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Удалить категорию</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3>Вы действительно хотите удалить категорию {{ $cat->name }}?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-danger btn-save btn-cat-delete">Подтвердить</button>
            </div>
        </div>
    </div>
</div>



<!-- Подтвердить удаление категории из числа подкатегорий -->
<div class="modal fade" id="CatDeleteChildModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Удалить категорию из числа подкатегорий</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Вы действительно хотите удалить <span class="cat-delete-child-name"></span> из числа дочерних категорий?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-danger btn-save btn-cat-delete-child">Подтвердить</button>
            </div>
        </div>
    </div>
</div>
@endif
