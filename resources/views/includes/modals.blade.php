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
                    <select class="form-control" name="parent_category_id" id="cat-new-parent-select">
                        <option value="null">Отсутствует</option>
                        @foreach (\App\Models\service_categories::where('type_id', 1)->orderBy('name')->get() as $scat)
                            <option value="{{ $scat->id }}"@if(isset($cat) && $cat->id == $scat->id) selected @endif>{{ $scat->name }}</option>
                        @endforeach
                    </select>
                </p>
                <p>
                    <b>Тип категории:</b><br>
                    <select class="form-control" name="type_id" id="cat-new-type-select">
                        @foreach (\App\Models\service_categories_types::all() as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
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

<!-- Вывод ошибок -->
<div class="modal fade" id="ErrorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Извините, на сервере произошла ошибка</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="p-modal-error"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ОК</button>
            </div>
        </div>
    </div>
</div>
