@extends('layout')

<?php
$cat = \App\Models\service_categories::where('id', $id);
$cat_exist = false;
if ($id == 0){
    $cat = new \App\Models\service_categories();
    $cat->name = 'Без категории';
    $cat_exist = true;
} else if ($cat->first() == null){
    $cat = new \App\Models\service_categories();
    $cat->name = 'Категории не существует';
} else {
    $cat = $cat->first();
    $cat_exist = true;
}
?>

@section('title')
{{ $cat->name }} - {{ \App\Settings::$title_site_name }} @endsection

@section('body')
    @if (!$cat_exist)
        <h3>Категории не существует</h3>
    @else
        <h2>
            Категория: <div class="d-inline-block value value-cat-name">{{ $cat->name }}</div>
            @if(\App\Functions::is_admin() && $id != 0)
                <button class="btn btn-cat-edit" title="Изменить название">
                    <img src="/images/icons/edit.svg">
                </button>
                <button class="btn btn-cat-delete" title="Удалить" type="button" data-toggle="modal" data-target="#CatDeleteModal">
                    <img src="/images/icons/trash.svg">
                </button>
            @endif
        </h2>

        @if (count($cat->children()->get()) != 0)
            <h3>Подкатегории:</h3>
            <table class="table subcats" width="100%">
                <tbody>
                @if(\App\Functions::is_admin())
                    <tr>
                        <th width="30"><input type="checkbox" class="select-all-checkbox"></th>
                        <th>Подкатегория</th>
                    </tr>
                @endif
                @foreach($cat->children()->orderBy('name', 'asc')->get() as $subcat)
                    <tr class="cat-{{ $subcat->id }}">
                        @if(\App\Functions::is_admin())<td><input type="checkbox" class="select-one-checkbox"></td>@endif
                        <td>
                            <p><a href="/category/{{ $subcat->id }}">{{ $subcat->name }}</a></p>
                        </td>
                    </tr>
                @endforeach
                @if(\App\Functions::is_admin())
                    <tr>
                        <td colspan="2">
                            С выбранными:
                            <select class="form-control select cats-move-select">
                                <option selected disabled>Переместить в...</option>
                                <option value="0">раздел "Без категории"</option>
                                @foreach(\App\Models\service_categories::where('type_id', $cat->type()->first()->id)->orderBy('name', 'asc')->get() as $scat)
                                    @if($cat->id != $scat->id)
                                        <option value="{{ $scat->id }}">{{ $scat->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button class="btn btn-danger btn-cats-delete">Удалить</button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn btn-primary btn-cat-add" type="button" data-toggle="modal" data-target="#CatAddModal">Добавить существующую</button>
                            &nbsp;&nbsp;
                            <button class="btn btn-primary btn-cat-new" type="button" data-toggle="modal" data-target="#CatNewModal">Создать новую</button>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        @elseif($id != 0)
            <h3>Нет подкатегорий</h3>
            @if(\App\Functions::is_admin())
                <p>
                    <button class="btn btn-primary btn-cat-add" type="button" data-toggle="modal" data-target="#CatAddModal">Добавить существующую</button>
                    <button class="btn btn-primary btn-cat-new" type="button" data-toggle="modal" data-target="#CatNewModal">Создать новую</button>
                </p>
            @endif
        @endif

        @if(count($cat->services()->get()) != 0 || $id == 0)
            <h3>Услуги:</h3>
            <?php
            $list = $cat->services()->orderBy('name', 'asc')->get();
            if ($id == 0)
                $list = \App\Models\services::orderBy('name', 'asc')->withTrashed()->get();
            ?>
            <table class="table services" width="100%">
                <tbody>
                @if(\App\Functions::is_admin())
                    <tr>
                        <th width="30"><input type="checkbox" class="select-all-checkbox"></th>
                        <th>Услуга</th>
                    </tr>
                @endif
                @foreach ($list as $service)
                    @if (($id == 0 && $service->categories()->first() == null) || $id != 0)
                        <tr class="service-{{ $service->id }}">
                            @if(\App\Functions::is_admin())<td><input type="checkbox" class="select-one-checkbox"></td>@endif
                            <td>
                                <p>
                                    <a href="/service/{{ $service->id }}"><b>{{ $service->name }}</b></a>
                                    @if($service->trashed())
                                        <span style="color: rgba(225, 25, 25, 0.75)"> - услуга больше не оказывается</span>
                                    @endif
                                </p>
                                <p>{!! mb_substr(strip_tags($service->description), 0, 250).'...' !!}</p>
                            </td>
                        </tr>
                    @endif
                @endforeach
                @if(\App\Functions::is_admin())
                    <tr>
                        <td colspan="2">
                            С выбранными:
                            <select class="form-control select select-move-services">
                                <option selected disabled>Переместить в...</option>
                                <option value="0">раздел "Без категории"</option>
                                @if($cat->id != 0)
                                    @foreach(\App\Models\service_categories::where('type_id', $cat->type_id)->get() as $scat)
                                        @if($cat->id != $scat->id)
                                            <option value="{{ $scat->id }}">{{ $scat->name }}</option>
                                        @endif
                                    @endforeach
                                @else
                                    @foreach(\App\Models\service_categories_types::all() as $type)
                                        <optgroup label="{{ $type->name }}">
                                            @foreach($type->categories()->get() as $scat)
                                                <option value="{{ $scat->id }}">{{ $scat->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                @endif
                            </select><br>
                            <button class="btn btn-danger btn-services-delete">Больше не оказываются</button>
                            <button class="btn btn-danger btn-services-restore">Восстановить</button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn btn-primary btn-service-new">Создать новую</button>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        @else
            <h3>Нет услуг</h3>
            <p>
                <button class="btn btn-primary btn-service-new">Создать новую</button>
            </p>
        @endif
    @endif

    @if(\App\Functions::is_admin())
        <!-- Добавление услуги -->
        <div class="modal fade" id="ServiceAddModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить существующую услугу</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><b>Выберите услугу:</b></p>
                        <select class="form-control" name="service-add-select" id="service-add-select">
                            <?php
                            $possible_child_services = \App\Models\services::all();
                            foreach ($possible_child_services as $pserv){
                                if (count($pserv->categories()->get()) == 0){
                                    echo '<option value="'.$pserv->id.'">'.$pserv->name.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                        <button type="button" class="btn btn-primary btn-save btn-service-add">Подтвердить</button>
                    </div>
                </div>
            </div>
        </div>

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
                            $possible_child_cats = \App\Models\service_categories::where('type_id', $cat->type_id)->orderBy('name')->get();
                            foreach ($possible_child_cats as $pcat){
                                // категория не может быть сама себе родителем и не может иметь своего родителя в качестве своего же ребенка
                                if ($cat->id != $pcat->id &&
                                    ($cat->parent()->first() == null ||
                                        ($cat->parent()->first() != null && $cat->parent()->first()->id != $pcat->id))){
                                    echo '<option data-type-id="'.$pcat->type()->first()->id.'" value="'.$pcat->id.'">'.$pcat->name.'</option>';
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
                        <h5 class="modal-title">Удалить выбранные категории</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h3>Вы действительно хотите удалить выбранные категории?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                        <button type="button" class="btn btn-danger btn-save btn-cats-delete">Подтвердить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Подтвердить удаление услуги -->
        <div class="modal fade" id="ServiceDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Услуги больше не оказываются</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h3>Выбранные услуги действительно больше не оказываются?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                        <button type="button" class="btn btn-danger btn-save btn-services-delete">Подтвердить</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    @if(\App\Functions::is_admin())
    <script>
        let selected_subcats = [];
        let selected_services = [];
        $(document).ready(function () {
            $('.cats-move-select').change(function(e){
                let select = this;
                selected_subcats.forEach(function(value, index, array){
                    if (value != $(select).val()){
                        let data = new FormData();
                        data.append('field', 'parent_category_id');
                        if ($(select).val() != '0')
                            data.append('value', $(select).val());
                        else
                            data.append('value', 'null');
                        $.ajax({
                            url: '/api/category/'+value+'/edit_field',
                            method: 'post',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function(data, status, xhr){
                                console.log([data, status, xhr]);
                                if (data.status == 'success') {
                                    selected_subcats.splice(index, 1);
                                    if (selected_subcats.length == 0)
                                        window.location.reload();
                                } else {
                                    display_error(xhr);
                                }
                            },
                            error: function(xhr){
                                display_error(xhr);

                            }
                        });
                    } else {
                        selected_subcats.splice(index, 1);
                    }
                });
            });

            $('.select-move-services').change(function(e){
                let select = this;
                selected_services.forEach(function(value, index, array){
                    if (value != $(select).val()){
                        let data = new FormData();
                        data.append('cat_from', @if($cat->id != 0) {!! $cat->id !!} @else 0 @endif);
                        data.append('cat_to', $(select).val());
                        $.ajax({
                            url: '/api/service/'+value+'/move',
                            method: 'post',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function(data, status, xhr){
                                console.log([data, status, xhr]);
                                if (data.status == 'success') {
                                    selected_services.splice(index, 1);
                                    if (selected_services.length == 0)
                                        window.location.reload();
                                } else {
                                    display_error(xhr);
                                }
                            },
                            error: function(xhr){
                                display_error(xhr);
                            }
                        });
                    } else {
                        selected_services.splice(index, 1);
                    }
                });
            });

            $('.btn-cat-edit').click(function(){
                if ($('.value-cat-name').children('input').length == 0) {
                    $('.value-cat-name').html('<input type="text" name="name" id="cat-name-input" value="' + $('.value-cat-name').text() + '"><button class="btn btn-link btn-cat-name-save"><img src="/images/icons/apply.svg"></button>');
                    $('#cat-name-input').focus();
                    $('#cat-name-input').on('keypress', function(e){
                        if (e.originalEvent.key == 'Enter'){
                            $('.btn-cat-name-save').click();
                        }
                    });
                    $('.btn-cat-name-save').click(function () {
                        if ($('#cat-name-input').val().trim() != ''){
                            let data = new FormData();
                            data.append('field', 'name');
                            data.append('value', $('#cat-name-input').val().trim());
                            $.ajax({
                                url: '/api/category/{{ $cat->id }}/edit_field',
                                method: 'post',
                                data: data,
                                processData: false,
                                contentType: false,
                                success: function(data, status, xhr){
                                    console.log([data, status, xhr]);
                                    if (data.status == 'success') {
                                        window.location.reload();
                                    } else {
                                        display_error(xhr);
                                    }
                                },
                                error: function(xhr){
                                    display_error(xhr);

                                }
                            });
                        }
                    });
                }
            });

            $('.btn-cat-delete').click(function(e){
                selected_subcats.push('{{ $cat->id }}');
            });

            $('.btn-cats-delete').click(function () {
                $('#CatDeleteModal').modal('toggle');
                if ($(this).hasClass('btn-save')){
                    let data = new FormData();
                    data.append('id_array', JSON.stringify(selected_subcats));
                    $.ajax({
                        url: '/api/category/delete_many',
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function (data, status, xhr) {
                            console.log([data, status, xhr]);
                            if (status == 'success'){
                                if (data.status == 'success'){
                                    window.location.reload();
                                }
                            }
                        },
                        error: function(xhr){
                            display_error(xhr);

                        }
                    });
                }
            });

            $('.btn-cat-add').click(function () {
                if ($(this).hasClass('btn-save')){
                    let data = new FormData();
                    data.append('field', 'parent_category_id');
                    data.append('value', '{{ $cat->id }}');
                    $.ajax({
                        url: '/api/category/'+$('#cat-add-select').val().trim()+'/edit_field',
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(data, status, xhr){
                            console.log([data, status, xhr]);
                            if (data.status == 'success') {
                                window.location.reload();
                            } else {
                                display_error(xhr);
                            }
                        },
                        error: function(xhr){
                            display_error(xhr);

                        }
                    });
                }
            });

            $('.btn-services-delete').click(function () {
                $('#ServiceDeleteModal').modal('toggle');
                if ($(this).hasClass('btn-save')){
                    let data = new FormData();
                    data.append('id_array', JSON.stringify(selected_services));
                    $.ajax({
                        url: '/api/service/delete_many',
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function (data, status, xhr) {
                            console.log([data, status, xhr]);
                            if (status == 'success'){
                                if (data.status == 'success'){
                                    window.location.reload();
                                }
                            }
                        },
                        error: function(xhr){
                            display_error(xhr);

                        }
                    });
                }
            });

            $('.btn-service-new').click(function(){
                window.location.assign('/service/new?cat={{ $cat->id }}');
            });

            $('.btn-services-restore').click(function () {
                let data = new FormData();
                data.append('id_array', JSON.stringify(selected_services));
                $.ajax({
                    url: '/api/service/restore_many',
                    method: 'post',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (data, status, xhr) {
                        console.log([data, status, xhr]);
                        if (status == 'success') {
                            if (data.status == 'success') {
                                window.location.reload();
                            } else {
                                display_error(xhr);
                            }
                        }
                    },
                    error: function (xhr) {
                        display_error(xhr);

                    }
                });
            });

            $('.select-all-checkbox').change(function (e) {
                $(this).parent().parent().parent().children('tr').children('td').children('.select-one-checkbox').prop('checked', $(this).prop('checked'));
                if ($(this).prop('checked')){
                    $('tr').each(function(index, elem){
                        if (elem.className.indexOf('cat-') != -1 ||
                            elem.className.indexOf('service-') != -1){
                            let classes = elem.className.split(' ');
                            classes.forEach(function(value, index, array){
                                if (value.split('-').length == 2){
                                    if (value.split('-')[0] == 'cat'){
                                        if (selected_subcats.indexOf(value.split('-')[1]) == -1)
                                            selected_subcats.push(value.split('-')[1]);
                                    }
                                    if (value.split('-')[0] == 'service'){
                                        if (selected_services.indexOf(value.split('-')[1]) == -1)
                                            selected_services.push(value.split('-')[1]);
                                    }
                                }
                            });
                        }
                    });
                } else {
                    if ($(this).parent().parent().parent().parent().hasClass('subcats'))
                        selected_subcats = [];
                    if ($(this).parent().parent().parent().parent().hasClass('services'))
                        selected_services = [];
                }
            });

            $('.select-one-checkbox').change(function (e) {
                let select = this;
                let classes = $(this).parent().parent()[0].className.split(' ');
                classes.forEach(function(value, index, array){
                    if (value.split('-').length == 2){
                        if (value.split('-')[0] == 'cat'){
                            if ($(select).prop('checked') && selected_subcats.indexOf(value.split('-')[1]) == -1){
                                selected_subcats.push(value.split('-')[1]);
                            } else {
                                selected_subcats.forEach(function(val, index, array){
                                    if (val == value.split('-')[1]){
                                        array.splice(index, 1);
                                    }
                                });
                            }
                        }
                        if (value.split('-')[0] == 'service'){
                            if ($(select).prop('checked') && selected_services.indexOf(value.split('-')[1]) == -1){
                                selected_services.push(value.split('-')[1]);
                            } else {
                                selected_services.forEach(function(val, index, array){
                                    if (val == value.split('-')[1]){
                                        array.splice(index, 1);
                                    }
                                });
                            }
                        }
                    }
                });
            });
        });
    </script>
    @endif
@endsection
