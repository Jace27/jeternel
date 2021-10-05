@extends('layout')

@section('title')
Создать услугу - {{ \App\Settings::$title_site_name }} @endsection

@section('body')
    <h2>Создать услугу</h2>
    <form action="/api/service/add" method="post" enctype="multipart/form-data" name="new_service_form">
        <?php
        $cat = new \App\Models\service_categories();
        $cat->name = 'Без категории';
        $cat->id = -1;
        if (isset($cat_id)){
            $temp_cat = \App\Models\service_categories::where('id', $cat_id)->first();
            if ($temp_cat != null){
                $cat = $temp_cat;
            }
        }
        ?>
            <h4>Название:</h4>
            <input type="text" name="name" class="form-control input" />

            <div class="border mt-3 mb-3 p-2">
                <h4>Категории по классу:</h4>
                <hr>
                <div id="class_categories">
                    @if($cat->id > -1 && $cat->type()->first()->name == 'По классу')
                        <div class="pt-1 pb-1">
                            <input type="text" disabled class="form-control select mr-1" style="max-width: calc(100% - 95px)" value="{{ $cat->name }}">
                            <input type="hidden" class="class_category" value="{{ $cat->id }}">
                            <button class="btn btn-primary btn-class-category-delete">Удалить</button>
                        </div>
                    @endif
                </div>
                <hr>
                <select id="select_class_category_add" class="form-control select" style="max-width: calc(100% - 110px)">
                    <option selected value="null">Выбрать...</option>
                    <?php $cat_type = \App\Models\service_categories_types::where('name', 'По классу')->first(); ?>
                    @foreach($cat_type->categories()->orderBy('name')->get() as $t_cat)
                        <option value="{{ $t_cat->id }}">{{ $t_cat->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-class-category-add">Добавить</button>
            </div>
            <input type="hidden" name="class_categories" class="input" />

            <div class="border mt-3 mb-3 p-2">
                <h4>Категории по проблемам клиента:</h4>
                <hr>
                <div id="trouble_categories">
                    @if($cat->id > -1 && $cat->type()->first()->name == 'По проблемам клиента')
                        <div class="pt-1 pb-1">
                            <input type="text" disabled class="form-control select mr-1" style="max-width: calc(100% - 95px)" value="{{ $cat->name }}">
                            <input type="hidden" class="trouble_category" value="{{ $cat->id }}">
                            <button class="btn btn-primary btn-trouble-category-delete">Удалить</button>
                        </div>
                    @endif
                </div>
                <hr>
                <select id="select_trouble_category_add" class="form-control select" style="max-width: calc(100% - 110px)">
                    <option selected value="null">Выбрать...</option>
                    <?php $cat_type = \App\Models\service_categories_types::where('name', 'По проблемам клиента')->first(); ?>
                    @foreach($cat_type->categories()->orderBy('name')->get() as $t_cat)
                        <option value="{{ $t_cat->id }}">{{ $t_cat->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-trouble-category-add">Добавить</button>
            </div>
            <input type="hidden" name="trouble_categories" class="input" />

            <div class="border mt-3 mb-3 p-2">
                <h4>Другие названия:</h4>
                <hr>
                <div id="other_names"></div>
                <hr>
                <input type="text" class="form-control select" style="max-width: calc(100% - 110px)" id="input-other-name-add">
                <button class="btn btn-primary btn-other-name-add">Добавить</button>
            </div>
            <input type="hidden" name="other_names" class="input" />

            <h4>Информирование о цене:</h4>
            <textarea name="instruct1" class="form-control input"></textarea>

            <div class="border mt-3 mb-3 p-2">
                <table class="table-borderless">
                    <tbody>
                    <tr>
                        <td>Филиалы, косметолог:</td>
                        <td><input type="number" style="max-width: 120px;" step="0.01" value="0.00" min="0" name="price_nonvip_low" class="form-control d-inline-block input">&nbsp;руб.</td>
                    </tr>
                    <tr>
                        <td>Филиалы, врач:</td>
                        <td><input type="number" style="max-width: 120px;" step="0.01" value="0.00" min="0" name="price_nonvip_high" class="form-control d-inline-block input">&nbsp;руб.</td>
                    </tr>
                    <tr>
                        <td>В13, косметолог:</td>
                        <td><input type="number" style="max-width: 120px;" step="0.01" value="0.00" min="0" name="price_vip_low" class="form-control d-inline-block input">&nbsp;руб.</td>
                    </tr>
                    <tr>
                        <td>В13, врач:</td>
                        <td><input type="number" style="max-width: 120px;" step="0.01" value="0.00" min="0" name="price_vip_high" class="form-control d-inline-block input">&nbsp;руб.</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <textarea name="instruct2" class="form-control input"></textarea>

            <div class="border mt-3 mb-3 p-2">
                <h4>Сопутствующие акции:</h4>
                <hr>
                <div id="promotions"></div>
                <hr>
                <select id="select_promotion_add" class="form-control select" style="max-width: calc(100% - 110px)">
                    <option selected value="null">Выбрать...</option>
                    @foreach(\App\Models\promotions::where('end', '>=', date('Y-m-d'))->orWhere('end', null)->get() as $promotion)
                        <option value="{{ $promotion->id }}">{{ $promotion->title }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-promotion-add">Добавить</button>
            </div>
            <input type="hidden" name="promotions" class="input" />

            <hr>
            <h4>Описание:</h4>
            <textarea name="description" id="description" class="input"></textarea>

            <hr>
            <h4>Подготовка:</h4>
            <textarea name="preparation" id="preparation" class="input"></textarea>

            <hr>
            <h4>Реабилитация:</h4>
            <textarea name="rehabilitation" id="rehabilitation" class="input"></textarea>

            <hr>
            <h4>Показания:</h4>
            <textarea name="indications" id="indications" class="input"></textarea>

            <hr>
            <h4>Противопоказания:</h4>
            <textarea name="contraindications" id="contraindications" class="input"></textarea>

            <hr>
            <h4>Курс:</h4>
            <textarea name="course" id="course" class="input"></textarea>

            <div class="border mt-3 mb-3 p-2">
                <h4>Используемые препараты:</h4>
                <hr>
                <div id="drugs"></div>
                <hr>
                <select class="form-control select" style="max-width: calc(100% - 110px)" id="select-drug-add">
                    <option value="null" disabled selected>Выбрать...</option>
                    <option value="add">Добавить новый препарат</option>
                    @foreach(\App\Models\drugs::all() as $drug)
                        <option value="{{ $drug->id }}">{{ $drug->name }}&nbsp;{{ $drug->manufacturer }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-drug-add">Добавить</button>
            </div>
            <input type="hidden" name="drugs" class="input">

            <div class="border mt-3 mb-3 p-2">
                <h4>Специалисты:</h4>
                <hr>
                <div id="performers"></div>
                <hr>
                <select class="form-control select" style="max-width: calc(100% - 110px)" id="select-performer-add">
                    <option value="null" disabled selected>Выбрать...</option>
                    <option value="add">Добавить нового специалиста</option>
                    @foreach(\App\Models\performers::all() as $performer)
                        <option value="{{ $performer->id }}">{{ $performer->last_name }}&nbsp;{{ $performer->first_name }}&nbsp;{{ $performer->second_name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-performer-add">Добавить</button>
            </div>
            <input type="hidden" name="performers" class="input">

            <br>
            <center><input type="submit" value="Создать" class="btn btn-outline-primary btn-service-save"></center>
        </form>

        <!-- Добавление препарата -->
        <div class="modal fade" id="DrugAddModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить новый препарат</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Название:
                            <input type="text" class="form-control drug-add-input" name="name">
                        </p>
                        <p>
                            Производитель:
                            <input type="text" class="form-control drug-add-input" name="manufacturer">
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                        <button type="button" class="btn btn-primary btn-save btn-drug-add">Подтвердить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Изменение препарата -->
        <div class="modal fade" id="DrugEditModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать препарат</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="form-control drug-edit-input" name="id">
                        <p>
                            Название:
                            <input type="text" class="form-control drug-edit-input" name="name">
                        </p>
                        <p>
                            Производитель:
                            <input type="text" class="form-control drug-edit-input" name="manufacturer">
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                        <button type="button" class="btn btn-primary btn-save btn-drug-edit">Подтвердить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Добавление специалиста -->
        <div class="modal fade" id="PerformerAddModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить нового специалиста</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            <input type="file" class="d-none photo-performer-add">
                            <center><button class="btn btn-outline-primary btn-performer-photo-choose">Выбрать фото</button></center>
                            <input type="hidden" name="photo" class="performer-add-input"><br>
                            <img class="d-none align-center">
                        <p></p>
                        <p>
                            Фамилия:
                            <input type="text" class="form-control performer-add-input" name="last_name">
                        </p>
                        <p>
                            Имя:
                            <input type="text" class="form-control performer-add-input" name="first_name">
                        </p>
                        <p>
                            Отчество:
                            <input type="text" class="form-control performer-add-input" name="second_name">
                        </p>
                        <p>
                            Специализация:
                            <select name="type_id" class="form-control select-performer-type performer-add-input">
                                @foreach(\App\Models\performers_types::all() as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </p>
                        <p>
                            Представление:
                            <textarea name="presentation" id="presentation" class="performer-add-input"></textarea>
                        </p>
                        <p>
                            График работы:
                            <input type="text" class="form-control performer-add-input" name="working_hours">
                        </p>

                        <div class="border mt-3 mb-3 p-2">
                            <h4>Филиалы:</h4>
                            <hr>
                            <div class="branches"></div>
                            <hr>
                            <select class="form-control select select-branch-add" style="max-width: calc(100% - 110px)">
                                <option value="null" disabled selected>Выбрать...</option>
                                <option value="add">Добавить новый филиал</option>
                                @foreach(\App\Models\branches::all() as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->address }}&nbsp;{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary btn-branch-add">Добавить</button>
                        </div>
                        <input type="hidden" name="branches" class="performer-add-input">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                        <button type="button" class="btn btn-primary btn-save btn-performer-add">Подтвердить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Изменение специалиста -->
        <div class="modal fade" id="PerformerEditModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать специалиста</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" class="performer-edit-input">

                        <input type="file" class="d-none photo-performer-add">
                        <center><button class="btn btn-outline-primary btn-performer-photo-choose">Выбрать фото</button>&nbsp;<button class="btn btn-outline-primary btn-performer-photo-delete">Удалить фото</button></center>
                        <input type="hidden" name="photo" class="performer-edit-input"><br>
                        <img class="d-none align-center">
                        <p></p>
                        <p>
                            Фамилия:
                            <input type="text" class="form-control performer-edit-input" name="last_name">
                        </p>
                        <p>
                            Имя:
                            <input type="text" class="form-control performer-edit-input" name="first_name">
                        </p>
                        <p>
                            Отчество:
                            <input type="text" class="form-control performer-edit-input" name="second_name">
                        </p>
                        <p>
                            Специализация:
                            <select name="type_id" class="form-control select-performer-type performer-edit-input">
                                @foreach(\App\Models\performers_types::all() as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </p>
                        <p>
                            Представление:
                            <textarea name="presentation" id="presentation-edit" class="performer-edit-input"></textarea>
                        </p>
                        <p>
                            График работы:
                            <input type="text" class="form-control performer-edit-input" name="working_hours">
                        </p>

                        <div class="border mt-3 mb-3 p-2">
                            <h4>Филиалы:</h4>
                            <hr>
                            <div class="branches"></div>
                            <hr>
                            <select class="form-control select select-branch-add" style="max-width: calc(100% - 110px)">
                                <option value="null" disabled selected>Выбрать...</option>
                                <option value="add">Добавить новый филиал</option>
                                @foreach(\App\Models\branches::all() as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->address }}&nbsp;{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary btn-branch-add">Добавить</button>
                        </div>
                        <input type="hidden" name="branches" class="performer-edit-input">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                        <button type="button" class="btn btn-primary btn-save btn-performer-edit">Подтвердить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Добавление филиала -->
        <div class="modal fade" id="BranchAddModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить новый филиал</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Название:
                            <input type="text" class="form-control branch-add-input" name="name">
                        </p>
                        <p>
                            Адрес:
                            <input type="text" class="form-control branch-add-input" name="address">
                        </p>
                        <p>
                            <label for="checkbox-isvip" class="form-control form-check-label">VIP-клиника&nbsp;&nbsp;&nbsp;<input type="checkbox" name="isvip" id="checkbox-isvip" class="branch-add-input"></label>

                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                        <button type="button" class="btn btn-primary btn-save btn-branch-add">Подтвердить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Изменение филиала -->
        <div class="modal fade" id="BranchEditModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать филиал</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="branch-edit-input" name="id">
                        <p>
                            Название:
                            <input type="text" class="form-control branch-edit-input" name="name">
                        </p>
                        <p>
                            Адрес:
                            <input type="text" class="form-control branch-edit-input" name="address">
                        </p>
                        <p>
                            <label for="checkbox-isvip-edit" class="form-control form-check-label">VIP-клиника&nbsp;&nbsp;&nbsp;<input type="checkbox" name="isvip" id="checkbox-isvip-edit" class="branch-edit-input"></label>

                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                        <button type="button" class="btn btn-primary btn-save btn-branch-edit">Подтвердить</button>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/validate.js"></script>
    <script>
        let selectors = [
            '#description',
            '#preparation',
            '#rehabilitation',
            '#indications',
            '#contraindications',
            '#course',
        ];

        let branches_block;

        $(document).ready(function(){
            if (localStorage.getItem('service_add_saved_data') != null && false)
                load_data();
            let dosave = true;
            $(window).on('close', function(e){
                if (dosave)
                    save_data();
            });

            for (let i = 0; i < selectors.length; i++) {
                tinymce.init({
                    selector: selectors[i],
                    <?php echo \App\Settings::$tinymce_settings; ?>
                });
            }

            reset_special_handlers();
            tinymce.init({
                selector: '#presentation',
                <?php echo \App\Settings::$tinymce_settings; ?>
            });
            tinymce.init({
                selector: '#presentation-edit',
                <?php echo \App\Settings::$tinymce_settings; ?>
            });

            $('#select_class_category_add').change(function(){
                $('.btn-class-category-add').click();
                $('#select_class_category_add').val('null');
            });
            $('.btn-class-category-add').click(function(e) {
                if ($('#select_class_category_add').val() != 'null') {
                    let ok = true;
                    $('.class_category').each(function (index, element) {
                        if ($(element).val().trim() == $('#select_class_category_add').val().trim()) {
                            ok = false;
                        }
                    });

                    if (ok) {
                        $('#class_categories').append(
                            '<div class="pt-1 pb-1">' +
                            '<input type="text" disabled class="form-control select mr-1" style="max-width: calc(100% - 95px)" value="' + $('#select_class_category_add option:selected').text().trim() + '">' +
                            '<input type="hidden" class="class_category" value="' + $('#select_class_category_add').val() + '">' +
                            '<button class="btn btn-primary btn-class-category-delete">Удалить</button>' +
                            '</div>'
                        );
                        reset_special_handlers();
                    }
                }
            });

            $('#select_trouble_category_add').change(function(){
                $('.btn-trouble-category-add').click();
                $('#select_trouble_category_add').val('null');
            });
            $('.btn-trouble-category-add').click(function(e) {
                if ($('#select_trouble_category_add').val() != 'null') {
                    let ok = true;
                    $('.trouble_category').each(function (index, element) {
                        if ($(element).val().trim() == $('#select_trouble_category_add').val().trim()) {
                            ok = false;
                        }
                    });

                    if (ok) {
                        $('#trouble_categories').append(
                            '<div class="pt-1 pb-1">' +
                            '<input type="text" disabled class="form-control select mr-1" style="max-width: calc(100% - 95px)" value="' + $('#select_trouble_category_add option:selected').text().trim() + '">' +
                            '<input type="hidden" class="trouble_category" value="' + $('#select_trouble_category_add').val() + '">' +
                            '<button class="btn btn-primary btn-trouble-category-delete">Удалить</button>' +
                            '</div>'
                        );
                        reset_special_handlers();
                    }
                }
            });

            $('.btn-other-name-add').click(function(e){
                if ($('#input-other-name-add').val().trim() != '') {
                    let ok = true;
                    $('.other_name').each(function(index, element){
                        if ($(element).val().trim() == $('#input-other-name-add').val().trim()){
                            ok = false;
                        }
                    });

                    if (ok) {
                        $('#other_names').append(
                            '<div class="pt-1 pb-1">' +
                            '<input type="text" class="form-control select other_name mr-1" style="max-width: calc(100% - 95px)" name="other_name' + ($('.other_name').length + 1) + '" value="' + $('#input-other-name-add').val().trim() + '">' +
                            '<button class="btn btn-primary btn-other-name-delete">Удалить</button>' +
                            '</div>'
                        );
                        $('#input-other-name-add').val('');
                        reset_special_handlers();
                    }
                }
            });

            $('#select-drug-add').change(function(e){
                if ($(this).val() == 'add'){
                    $('#DrugAddModal').modal('show');
                } else {
                    $('.btn-drug-add').click();
                }
                $('#select-drug-add').val('null');
            });
            $('#select-performer-add').change(function(e){
                if ($(this).val() == 'add'){
                    branches_block = $('PerformerAddModal').find('.branches');
                    $('#PerformerAddModal').modal('show');
                } else {
                    $('.btn-performer-add').click();
                }
                $('#select-performer-add').val('null');
            });

            $('.btn-drug-add').click(function(e){
                if (!$(this).hasClass('btn-save')) {
                    if ($('#select-drug-add').val() != 'null' && $('#select-drug-add').val() != 'add') {
                        let ok = true;
                        $('.drug').each(function (index, element) {
                            if ($(element).val().trim() == $('#select-drug-add').val().trim()) {
                                ok = false;
                            }
                        });

                        if (ok) {
                            $('#drugs').append(
                                '<div class="pt-1 pb-1" id="drug-' + $('#select-drug-add').val() + '">' +
                                '<input type="text" disabled class="form-control select mr-1 drug-name" style="max-width: calc(100% - 400px)" value="' + $('#select-drug-add option:selected').text().trim() + '">' +
                                '<input type="text" class="form-control select mr-1 drug-volume" style="max-width: 200px">' +
                                '<input type="hidden" class="drug" value="' + $('#select-drug-add').val() + '">' +
                                '<button class="btn btn-primary btn-drug-edit mr-1">Изменить</button>' +
                                '<button class="btn btn-primary btn-drug-delete">Удалить</button>' +
                                '</div>'
                            );
                            reset_special_handlers();
                        }
                    }
                }
                if ($(this).hasClass('btn-save')) {
                    if ($('.drug-add-input[name=name]').val().trim() != '') {
                        let data = new FormData();
                        $('.drug-add-input').each(function (index, element) {
                            data.append($(element).attr('name'), $(element).val());
                        });
                        $.ajax({
                            url: '/api/drug/add',
                            method: 'post',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (data, status, xhr) {
                                if (status == 'success') {
                                    if (data.status == 'success') {
                                        $('#select-drug-add').append(
                                            '<option selected value="' + data.id + '">' + $('.drug-add-input[name=name]').val().trim() + '</option>'
                                        );
                                        $('#DrugAddModal').modal('hide');
                                        $('.drug-add-input').each(function (index, element) {
                                            $(element).val('');
                                        });
                                    } else {
                                        display_error(xhr);
                                    }
                                }
                            },
                            error: function (xhr) {
                                display_error(xhr);

                            }
                        });
                    }
                }
            });

            $('.btn-performer-photo-delete').click(function(){
                let img = $(this).parent().parent().children('img');
                let photo = $(this).parent().parent().children('[name=photo]');
                let performer_id = $(this).parent().parent().children('[name=id]').val();
                $.ajax({
                    url: '/api/images/performers/'+encodeURIComponent(photo.val())+'/delete',
                    method: 'get',
                    data: null,
                    processData: false,
                    contentType: false,
                    success: function(data, status, xhr){
                        if (status == 'success'){
                            if (data.status == 'deleted'){
                                photo.val('null');
                                img.addClass('d-none');
                                let data = new FormData();
                                data.append('field', 'photo');
                                data.append('value', 'null');
                                $.ajax({
                                    url: '/api/performer/'+performer_id+'/edit_field',
                                    method: 'post',
                                    data: data,
                                    processData: false,
                                    contentType: false,
                                    error: function(xhr){
                                        display_error(xhr);

                                    }
                                });
                            }
                            if (data.status == 'not found'){
                                display_error('<p>Файл не найден</p>');

                            }
                        }
                    },
                    error: function(xhr){
                        console.log(xhr);
                        display_error(xhr);

                    }
                });
            });
            $('.btn-performer-photo-choose').click(function(){
                $(this).parent().parent().children('.photo-performer-add').click();
            });
            $('.photo-performer-add').change(function(e){
                if (this.files.length > 0){
                    let data = new FormData();
                    data.append('upload', this.files[0]);
                    $.ajax({
                        url: '/api/upload/performer',
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(data, status, xhr){
                            if (status == 'success'){
                                if (data.status == 'success'){
                                    $('.btn-performer-photo-choose').parent().parent().children('[name=photo]').val(data.file_name);
                                    $('.btn-performer-photo-choose').parent().parent().children('img').removeClass('d-none');
                                    $('.btn-performer-photo-choose').parent().parent().children('img').attr('src', '/images/performers/'+data.file_name+'?t='+(new Date).getTime());
                                }
                            }
                        },
                        error: function(xhr){
                            display_error(xhr);

                        }
                    });
                }
            });
            $('.select-branch-add').change(function(e){
                branches_block = $(this).parent();
                if ($(this).val() == 'add'){
                    $('#BranchAddModal').modal('show');
                } else {
                    branches_block.children('.btn-branch-add').click();
                }
                $('.select-branch-add').val('null');
            });
            $('.btn-branch-add').click(function(e){
                if (!$(this).hasClass('btn-save')){
                    branches_block = $(this).parent();
                    let ok = true;
                    branches_block.children('.branches').children('div').children('.branch').each(function (index, element) {
                        if ($(element).val().trim() == branches_block.children('.select-branch-add').val().trim()) {
                            ok = false;
                        }
                    });

                    if (ok){
                        branches_block.children('.branches').append(
                            '<div class="pt-1 pb-1" id="branch-'+branches_block.children('.select-branch-add').val()+'">' +
                            '<input type="text" disabled class="form-control select mr-1 branch-name" style="max-width: calc(100% - 200px)" value="' + branches_block.children('.select-branch-add').children('option:selected').text().trim() + '">' +
                            '<input type="hidden" class="branch" value="' + branches_block.children('.select-branch-add').val() + '">' +
                            '<button class="btn btn-primary btn-branch-edit mr-1">Изменить</button>' +
                            '<button class="btn btn-primary btn-branch-delete">Удалить</button>' +
                            '</div>'
                        );
                        reset_special_handlers();
                    }
                }
                if ($(this).hasClass('btn-save')) {
                    if ($('.branch-add-input[name=address]').val().trim() != '') {
                        let data = new FormData();
                        $('.branch-add-input').each(function (index, element) {
                            if (!$(element).attr('name') != 'isvip') {
                                data.append($(element).attr('name'), $(element).val());
                            }
                            if ($(element).attr('name') == 'isvip'){
                                if ($(element)[0].checked){
                                    data.append($(element).attr('name'), 1);
                                } else {
                                    data.append($(element).attr('name'), 0);
                                }
                            }
                        });
                        $.ajax({
                            url: '/api/branch/add',
                            method: 'post',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (data, status, xhr) {
                                if (status == 'success') {
                                    if (data.status == 'success') {
                                        $('.select-branch-add').append(
                                            '<option selected value="' + data.id + '">' + $('.branch-add-input[name=address]').val().trim() + ' ' + $('.branch-add-input[name=name]').val().trim() + '</option>'
                                        );
                                        $('#BranchAddModal').modal('hide');
                                        $('.branch-add-input').each(function (index, element) {
                                            $(element).val('');
                                        });
                                        $('.branches').html('');
                                    } else {
                                        display_error(xhr);
                                    }
                                }
                            },
                            error: function (xhr) {
                                display_error(xhr);

                            }
                        });
                    }
                }
            });
            $('.btn-performer-add').click(function(e){
                if (!$(this).hasClass('btn-save')) {
                    if ($('#select-performer-add').val() != 'null' && $('#select-performer-add').val() != 'add') {
                        let ok = true;
                        $('.performer').each(function (index, element) {
                            if ($(element).val().trim() == $('#select-performer-add').val().trim()) {
                                ok = false;
                            }
                        });

                        if (ok) {
                            $('#performers').append(
                                '<div class="pt-1 pb-1" id="performer-'+$('#select-performer-add').val()+'">' +
                                '<input type="text" disabled class="form-control select mr-1 performer-name" style="max-width: calc(100% - 300px)" value="' + $('#select-performer-add option:selected').text().trim() + '">' +
                                '<input type="time" class="form-control select mr-1 performer-time" style="max-width: 100px">' +
                                '<input type="hidden" class="performer" value="' + $('#select-performer-add').val() + '">' +
                                '<button class="btn btn-primary btn-performer-edit mr-1">Изменить</button>' +
                                '<button class="btn btn-primary btn-performer-delete">Удалить</button>' +
                                '</div>'
                            );
                            reset_special_handlers();
                        }
                    }
                } else if ($(this).hasClass('btn-save')) {
                    branches_block = $(this).parent().parent().children('.modal-body').children('.border');
                    if ($('.performer-add-input[name=first_name]').val().trim() != '' && $('.performer-add-input[name=last_name]').val().trim() != '') {
                        tinymce.get('presentation').save();

                        let branches = [];
                        branches_block.children('.branches').children('div').children('.branch').each(function (index, element) {
                            if ($(element).val().trim() != '')
                                branches.push($(element).val().trim());
                        });
                        $('[name=branches]').val(JSON.stringify(branches));

                        let data = new FormData();
                        $('.performer-add-input').each(function (index, element) {
                            data.append($(element).attr('name'), $(element).val());
                        });
                        $.ajax({
                            url: '/api/performer/add',
                            method: 'post',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (data, status, xhr) {
                                if (status == 'success') {
                                    if (data.status == 'success') {
                                        $('#select-performer-add').append(
                                            '<option selected value="' + data.id + '">' + $('.performer-add-input[name=last_name]').val().trim() + ' ' + $('.performer-add-input[name=first_name]').val().trim() + ' ' + $('.performer-add-input[name=second_name]').val().trim() + '</option>'
                                        );
                                        $('.btn-performer-add').click();
                                        $('#PerformerAddModal').modal('hide');
                                        $('.performer-add-input').each(function (index, element) {
                                            $(element).val('');
                                        });
                                        $('.branches').html('');
                                    } else {
                                        display_error(xhr);
                                    }
                                }
                            },
                            error: function (xhr) {
                                display_error(xhr);

                            }
                        });
                    }
                }
            });

            $('#select_promotion_add').change(function(){
                $('.btn-promotion-add').click();
                $('#select_promotion_add').val('null');
            });
            $('.btn-promotion-add').click(function(e) {
                if ($('#select_promotion_add').val() != 'null') {
                    let ok = true;
                    $('.promotion').each(function (index, element) {
                        if ($(element).val().trim() == $('#select_promotion_add').val().trim()) {
                            ok = false;
                        }
                    });

                    if (ok) {
                        $('#promotions').append(
                            '<div class="pt-1 pb-1">' +
                            '<input type="text" disabled class="form-control select mr-1" style="max-width: calc(100% - 95px)" value="' + $('#select_promotion_add option:selected').text().trim() + '">' +
                            '<input type="hidden" class="promotion" value="' + $('#select_promotion_add').val() + '">' +
                            '<button class="btn btn-primary btn-promotion-delete">Удалить</button>' +
                            '</div>'
                        );
                        reset_special_handlers();
                    }
                }
            });

            let last_errors = [];
            let validator = new FormValidator('new_service_form', [
                {
                    name: 'name',
                    display: 'Название',
                    rules: 'required'
                },
            ], function(errors, event){
                last_errors = errors;
            });
            validator.setMessage('required', 'Поле %s должно быть заполнено');
            $('form[name=new_service_form]').submit(function(e) {
                e.preventDefault();
            });

            $('.btn-service-save').click(function(e){
                format_special_fields('in');
                if ($('input[name=name]').val().trim() != ''){
                    $('input[name=name]').val($('input[name=name]').val().replace('́', '&#769;'));
                    let data = new FormData();
                    $('.input').each(function(index, element){
                        let val = $(element).val();
                        if (val == '') val = '&nbsp;';
                        data.append($(element).attr('name'), val);
                    });
                    $.ajax({
                        url: '/api/service/add',
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(data, status, xhr){
                            if (status == 'success'){
                                if (data.status == 'success'){
                                    dosave = false;
                                    window.location.assign('/service/'+data.id);
                                }
                            }
                        },
                        error: function(xhr){
                            display_error(xhr);

                        }
                    })
                }
            });
            $(window).on('beforeunload', function(){
                save_data();
            });

            reset_special_handlers();
        });

        function save_data(){
            format_special_fields('in');
            let data = {};
            $('.input').each(function(index, element){
                let val = $(element).val();
                data[$(element).attr('name')] = val;
            });
            localStorage.setItem('service_add_saved_data', JSON.stringify(data));
        }
        function load_data(){
            let data = localStorage.getItem('service_add_saved_data');
            if (data != null){
                data = JSON.parse(data);
                for (let key in data){
                    $('.input[name='+key+']').val(data[key]);
                }
                format_special_fields('out');
                reset_special_handlers();
            }
        }

        function format_special_fields(direction){
            if (direction == 'in') {

                for (let i = 0; i < selectors.length; i++) {
                    tinymce.get(selectors[i].substr(1)).save();
                }

                let other_names = [];
                $('.other_name').each(function (index, element) {
                    if ($(element).val().trim() != '')
                        other_names.push($(element).val().trim());
                });
                $('[name=other_names]').val(JSON.stringify(other_names));

                let class_categories = [];
                $('.class_category').each(function (index, element) {
                    if ($(element).val().trim() != '')
                        class_categories.push($(element).val().trim());
                });
                $('[name=class_categories]').val(JSON.stringify(class_categories));

                let trouble_categories = [];
                $('.trouble_category').each(function (index, element) {
                    if ($(element).val().trim() != '')
                        trouble_categories.push($(element).val().trim());
                });
                $('[name=trouble_categories]').val(JSON.stringify(trouble_categories));

                let drugs = [];
                $('.drug').each(function (index, element) {
                    if ($(element).val().trim() != '')
                        drugs.push({ id: $(element).val().trim(), volume: $(element).parent().children('.drug-volume').val() });
                });
                $('[name=drugs]').val(JSON.stringify(drugs));

                let performers = [];
                $('.performer').each(function (index, element) {
                    if ($(element).val().trim() != '')
                        performers.push({ id: $(element).val().trim(), time: $(element).parent().children('.performer-time').val() });
                });
                $('[name=performers]').val(JSON.stringify(performers));

                let promotions = [];
                $('.promotion').each(function (index, element) {
                    if ($(element).val().trim() != '')
                        promotions.push($(element).val().trim());
                });
                $('[name=promotions]').val(JSON.stringify(promotions));

            } else if (direction == 'out'){

                let other_names = JSON.parse($('[name=other_names]').val());
                other_names.forEach(function(elem, i, arr){
                    $('#other_names').append(
                        '<div class="pt-1 pb-1">' +
                        '<input type="text" class="form-control select other_name mr-1" style="max-width: calc(100% - 95px)" value="'+elem+'">' +
                        '<button class="btn btn-primary btn-other-name-delete">Удалить</button>' +
                        '</div>'
                    );
                });

                let class_categories = JSON.parse($('[name=class_categories]').val());
                if (class_categories.length > 0) {
                    $('#class_categories').html('');
                    class_categories.forEach(function (c_cat, i, arr) {
                        let value = '';
                        $('#select_class_category_add option').each(function (i, opt) {
                            if ($(opt).attr('value') == c_cat) {
                                value = $(opt).text();
                            }
                        });
                        $('#class_categories').append(
                            '<div class="pt-1 pb-1">' +
                            '<input type="text" disabled class="form-control select mr-1" style="max-width: calc(100% - 95px)" value="' + value + '">' +
                            '<input type="hidden" class="class_category" value="' + c_cat + '">' +
                            '<button class="btn btn-primary btn-class-category-delete">Удалить</button>' +
                            '</div>'
                        );
                    });
                }

                let trouble_categories = JSON.parse($('[name=trouble_categories]').val());
                if (trouble_categories.length > 0) {
                    $('#trouble_categories').html('');
                    trouble_categories.forEach(function (t_cat, i, arr) {
                        let value = '';
                        $('#select_trouble_category_add option').each(function (i, opt) {
                            if ($(opt).attr('value') == t_cat) {
                                value = $(opt).text();
                            }
                        });
                        $('#trouble_categories').append(
                            '<div class="pt-1 pb-1">' +
                            '<input type="text" disabled class="form-control select mr-1" style="max-width: calc(100% - 95px)" value="' + value + '">' +
                            '<input type="hidden" class="trouble_category" value="' + t_cat + '">' +
                            '<button class="btn btn-primary btn-trouble-category-delete">Удалить</button>' +
                            '</div>'
                        );
                    });
                }

                let drugs = JSON.parse($('[name=drugs]').val());
                if (drugs.length > 0) {
                    $('#drugs').html('');
                    drugs.forEach(function (drug, i, arr) {
                        let value = '';
                        $('#select-drug-add option').each(function (i, opt) {
                            if ($(opt).attr('value') == drug.id) {
                                value = $(opt).text();
                            }
                        });
                        $('#drugs').append(
                            '<div class="pt-1 pb-1" id="drug-' + drug.id + '">' +
                            '<input type="text" disabled class="form-control select mr-1 drug-name" style="max-width: calc(100% - 400px)" value="' + value + '">' +
                            '<input type="text" class="form-control select mr-1 drug-volume" style="max-width: 200px" value="' + drug.volume + '">' +
                            '<input type="hidden" class="drug" value="' + drug.id + '">' +
                            '<button class="btn btn-primary btn-drug-edit mr-1">Изменить</button>' +
                            '<button class="btn btn-primary btn-drug-delete">Удалить</button>' +
                            '</div>'
                        );
                    });
                }

                let performers = JSON.parse($('[name=performers]').val());
                if (performers.length > 0) {
                    $('#performers').html('');
                    performers.forEach(function (performer, i, arr) {
                        let value = '';
                        $('#select-performer-add option').each(function (i, opt) {
                            if ($(opt).attr('value') == performer.id) {
                                value = $(opt).text();
                            }
                        });
                        $('#performers').append(
                            '<div class="pt-1 pb-1" id="performer-'+performer.id+'">' +
                            '<input type="text" disabled class="form-control select mr-1" style="max-width: calc(100% - 300px)" value="' + value + '">' +
                            '<input type="time" class="form-control select mr-1 performer-time" style="max-width: 100px" value="'+performer.time+'">' +
                            '<input type="hidden" class="performer" value="' + performer.id + '">' +
                            '<button class="btn btn-primary btn-performer-edit mr-1">Изменить</button>' +
                            '<button class="btn btn-primary btn-performer-delete">Удалить</button>' +
                            '</div>'
                        );
                    });
                }

                let promotions = JSON.parse($('[name=promotions]').val());
                if (promotions.length > 0) {
                    $('#promotions').html('');
                    promotions.forEach(function (prom, i, arr) {
                        let value = '';
                        $('#select_promotion_add option').each(function (i, opt) {
                            if ($(opt).attr('value') == prom) {
                                value = $(opt).text();
                            }
                        });
                        $('#promotions').append(
                            '<div class="pt-1 pb-1">' +
                            '<input type="text" disabled class="form-control select mr-1" style="max-width: calc(100% - 95px)" value="' + value + '">' +
                            '<input type="hidden" class="promotion" value="' + c_cat + '">' +
                            '<button class="btn btn-primary btn-promotion-delete">Удалить</button>' +
                            '</div>'
                        );
                    });
                }
            }
        }

        function reset_special_handlers(){

            $('.btn-class-category-delete').unbind('click');
            $('.btn-class-category-delete').click(function (e) {
                $(this).parent().remove();
            });

            $('.btn-trouble-category-delete').unbind('click');
            $('.btn-trouble-category-delete').click(function (e) {
                $(this).parent().remove();
            });

            $('.btn-other-name-delete').unbind('click');
            $('.btn-other-name-delete').click(function (e) {
                $(this).parent().remove();
            });

            $('.btn-drug-delete').unbind('click');
            $('.btn-drug-delete').click(function (e) {
                $(this).parent().remove();
            });

            $('.btn-drug-edit').unbind('click');
            $('.btn-drug-edit').click(function (e) {
                if (!$(this).hasClass('btn-save')) {
                    $.ajax({
                        url: '/api/drug/' + $(this).parent().children('[type=hidden]').val() + '/get',
                        method: 'get',
                        data: null,
                        processData: false,
                        contentType: 'json',
                        success: function (data, status, xhr) {
                            if (status == 'success') {
                                if (data.status == 'found') {
                                    $('.drug-edit-input[name=id]').val(data.object.id);
                                    $('.drug-edit-input[name=name]').val(data.object.name);
                                    $('.drug-edit-input[name=manufacturer]').val(data.object.manufacturer);
                                    $('#DrugEditModal').modal('show');
                                }
                                if (data.status == 'error') {

                                }
                            }
                        },
                        error: function (xhr) {
                            display_error(xhr);

                        }
                    })
                }
                if ($(this).hasClass('btn-save')) {
                    if ($('.drug-edit-input[name=name]').val().trim() != '') {
                        let id = -1;
                        let data = new FormData();
                        $('.drug-edit-input').each(function (index, element) {
                            if ($(element).attr('name') == 'id') {
                                id = $(element).val();
                            }
                            if ($(element).attr('name') != 'id') {
                                data.append($(element).attr('name'), $(element).val());
                            }
                        });
                        $.ajax({
                            url: '/api/drug/' + id + '/edit',
                            method: 'post',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (data, status, xhr) {
                                if (status == 'success') {
                                    if (data.status == 'success') {
                                        let text = '';
                                        $('.drug-edit-input').each(function (index, element) {
                                            if ($(element).attr('name') != 'id') {
                                                text += $(element).val() + ' ';
                                            }
                                        });
                                        $('#select-drug-add').children('option').each(function (index, element) {
                                            if ($(element).attr('value') == id) {
                                                $(element).text(text);
                                            }
                                        });
                                        $('#drug-' + id).children('input.drug-name').val(text);
                                        $('#DrugEditModal').modal('hide');
                                        $('.drug-edit-input').each(function (index, element) {
                                            $(element).val('');
                                        });
                                    } else {
                                        display_error(xhr);
                                    }
                                }
                            },
                            error: function (xhr) {
                                display_error(xhr);

                            }
                        });
                    }
                }
            });

            $('.btn-branch-delete').unbind('click');
            $('.btn-branch-delete').click(function (e) {
                $(this).parent().remove();
            });

            $('.btn-branch-edit').unbind('click');
            $('.btn-branch-edit').click(function (e) {
                if (!$(this).hasClass('btn-save')) {
                    $.ajax({
                        url: '/api/branch/' + $(this).parent().children('[type=hidden]').val() + '/get',
                        method: 'get',
                        data: null,
                        processData: false,
                        contentType: 'json',
                        success: function (data, status, xhr) {
                            if (status == 'success') {
                                if (data.status == 'found') {
                                    $('.branch-edit-input[name=id]').val(data.object.id);
                                    $('.branch-edit-input[name=name]').val(data.object.name);
                                    $('.branch-edit-input[name=address]').val(data.object.address);
                                    if (data.object.isvip == 1)
                                        $('.branch-edit-input[name=isvip]').prop('checked', true);
                                    $('#BranchEditModal').modal('show');
                                }
                            }
                        },
                        error: function (xhr) {
                            display_error(xhr);

                        }
                    });
                }
                if ($(this).hasClass('btn-save')){
                    if ($('.branch-edit-input[name=address]').val().trim() != '') {
                        let id = -1;
                        let data = new FormData();
                        $('.branch-edit-input').each(function (index, element) {
                            if ($(element).attr('name') == 'id') {
                                id = $(element).val();
                            }
                            if ($(element).attr('name') != 'id') {
                                if ($(element).attr('name') != 'isvip') {
                                    data.append($(element).attr('name'), $(element).val());
                                } else {
                                    if ($(element)[0].checked)
                                        data.append($(element).attr('name'), 1);
                                    else
                                        data.append($(element).attr('name'), 0);
                                }
                            }
                        });
                        $.ajax({
                            url: '/api/branch/' + id + '/edit',
                            method: 'post',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (data, status, xhr) {
                                if (status == 'success') {
                                    if (data.status == 'success') {
                                        let text = '';
                                        $('.branch-edit-input').each(function (index, element) {
                                            if ($(element).attr('name') != 'id' && $(element).attr('name') != 'isvip') {
                                                text += $(element).val() + ' ';
                                            }
                                        });
                                        branches_block.children('.select-branch-add').children('option').each(function (index, element) {
                                            if ($(element).attr('value') == id) {
                                                $(element).text(text);
                                            }
                                        });
                                        $('#branch-' + id).children('input[type=text]').val(text);
                                        $('#BranchEditModal').modal('hide');
                                        $('.branch-edit-input').each(function (index, element) {
                                            $(element).val('');
                                        });
                                    } else {
                                        display_error(xhr);
                                    }
                                }
                            },
                            error: function (xhr) {
                                display_error(xhr);

                            }
                        });
                    }
                }
            });

            $('.btn-performer-delete').unbind('click');
            $('.btn-performer-delete').click(function (e) {
                $(this).parent().remove();
            });

            $('.btn-performer-edit').unbind('click');
            $('.btn-performer-edit').click(function (e) {
                branches_block = $('PerformerEditModal').find('.branches').parent();
                if (!$(this).hasClass('btn-save')) {
                    $.ajax({
                        url: '/api/performer/' + $(this).parent().children('[type=hidden]').val() + '/get',
                        method: 'get',
                        data: null,
                        processData: false,
                        contentType: 'json',
                        success: function (data, status, xhr) {
                            if (status == 'success') {
                                if (data.status == 'found') {
                                    $('.performer-edit-input[name=id]').val(data.object.id);
                                    if (data.object.photo != null) {
                                        $('.performer-edit-input[name=photo]').parent().children('img').attr('src', '/images/performers/' + data.object.photo + '?t=' + (new Date).getTime());
                                        $('.performer-edit-input[name=photo]').parent().children('img').removeClass('d-none');
                                    } else {
                                        $('.performer-edit-input[name=photo]').parent().children('img').addClass('d-none');
                                    }
                                    $('.performer-edit-input[name=photo]').val(data.object.photo);
                                    $('.performer-edit-input[name=last_name]').val(data.object.last_name);
                                    $('.performer-edit-input[name=first_name]').val(data.object.first_name);
                                    $('.performer-edit-input[name=second_name]').val(data.object.second_name);
                                    $('.performer-edit-input[name=type_id]').val(data.object.type_id);
                                    $('.performer-edit-input[name=presentation]').val(data.object.presentation);
                                    $('.performer-edit-input[name=working_hours]').val(data.object.working_hours);
                                    tinymce.get('presentation-edit').load();
                                    $('.branches').html('');
                                    data.object.branches.forEach(function(branch, i, arr){
                                        $('#PerformerEditModal').find('.branches').append(
                                            '<div class="pt-1 pb-1" id="branch-'+branch.id+'">' +
                                            '<input type="text" disabled class="form-control select mr-1 branch-name" style="max-width: calc(100% - 200px)" value="' + branch.address + ' ' + (branch.name != null ? branch.name : '') + '">' +
                                            '<input type="hidden" class="branch" value="' + branch.id + '">' +
                                            '<button class="btn btn-primary btn-branch-edit mr-1">Изменить</button>' +
                                            '<button class="btn btn-primary btn-branch-delete">Удалить</button>' +
                                            '</div>'
                                        );
                                    });
                                    reset_special_handlers();
                                    $('#PerformerEditModal').modal('show');
                                }
                            }
                        },
                        error: function (xhr) {
                            display_error(xhr);
                        }
                    })
                }
                if ($(this).hasClass('btn-save')) {
                    if ($('.performer-edit-input[name=first_name]').val().trim() != '' && $('.performer-edit-input[name=last_name]').val().trim() != '') {
                        let id = -1;

                        tinymce.get('presentation-edit').save();

                        let branches = [];
                        branches_block.children('.branches').children('div').children('.branch').each(function (index, element) {
                            if ($(element).val().trim() != '')
                                branches.push($(element).val().trim());
                        });
                        $('[name=branches]').val(JSON.stringify(branches));

                        let data = new FormData();
                        $('.performer-edit-input').each(function (index, element) {
                            if ($(element).attr('name') == 'id') {
                                id = $(element).val();
                            }
                            if ($(element).attr('name') != 'id') {
                                data.append($(element).attr('name'), $(element).val());
                            }
                        });
                        $.ajax({
                            url: '/api/performer/' + id + '/edit',
                            method: 'post',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (data, status, xhr) {
                                if (status == 'success') {
                                    if (data.status == 'success') {
                                        let text = '';
                                        $('.performer-edit-input').each(function (index, element) {
                                            if ($(element).attr('name') == 'last_name' || $(element).attr('name') == 'first_name' || $(element).attr('name') == 'second_name') {
                                                text += $(element).val() + ' ';
                                            }
                                        });
                                        $('#select-performer-add').children('option').each(function (index, element) {
                                            if ($(element).attr('value') == id) {
                                                $(element).text(text);
                                            }
                                        });
                                        $('#performer-' + id).children('input[type=text]').val(text);
                                        $('#PerformerEditModal').modal('hide');
                                        $('.performer-edit-input').each(function (index, element) {
                                            $(element).val('');
                                        });
                                        $('.branches').html('');
                                    } else {
                                        display_error(xhr);
                                    }
                                }
                            },
                            error: function (xhr) {
                                display_error(xhr);
                                $('#ErrorModal').modal('show');
                            }
                        });
                    }
                }
            });

            $('.btn-promotion-delete').unbind('click');
            $('.btn-promotion-delete').click(function (e) {
                $(this).parent().remove();
            });

        }
    </script>
@endsection
