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
