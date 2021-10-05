@extends('layout')

<?php
$performer = \App\Models\performers::where('id', $id)->first();
$performer_exist = true;
if ($performer == null){
    $performer_exist = false;
    $performer = new \App\Models\performers();
    $performer->id = -1;
    $performer->name = 'Специалиста не существует';
}
?>

@section('title')
Редактировать специалиста - {{ \App\Settings::$title_site_name }} @endsection

@section('body')
    @if(!$performer_exist)
        <h3>Специалиста не существует</h3>
    @else
    <h3>Редактировать специалиста</h3>

    <input type="file" class="d-none photo-performer-add">
    <center><button class="btn btn-outline-primary btn-performer-photo-choose">Выбрать фото</button></center>
    <input type="hidden" name="photo" class="performer-edit-input" value="{{ $performer->photo }}"><br>
    <center>
    @if($performer->photo == null)
        <img class="d-none align-center img-performer-photo">
    @else
        <img class="align-center img-performer-photo" src="/images/performers/{{ $performer->photo }}">
    @endif
    </center>
    <p></p>
    <p>
        Фамилия:
        <input type="text" class="form-control performer-edit-input" name="last_name" value="{{ $performer->last_name }}">
    </p>
    <p>
        Имя:
        <input type="text" class="form-control performer-edit-input" name="first_name" value="{{ $performer->first_name }}">
    </p>
    <p>
        Отчество:
        <input type="text" class="form-control performer-edit-input" name="second_name" value="{{ $performer->second_name }}">
    </p>
    <p>
        Специализация:
        <select name="type_id" class="form-control select-performer-type performer-edit-input">
            @foreach(\App\Models\performers_types::all() as $type)
                <option value="{{ $type->id }}" @if($type->id == $performer->type_id) selected @endif>{{ $type->name }}</option>
            @endforeach
        </select>
    </p>
    <p>
        Представление:
        <textarea name="presentation" id="presentation" class="performer-edit-input">{{ $performer->presentation }}</textarea>
    </p>
    <p>
        График работы:
        <input type="text" class="form-control performer-edit-input" name="working_hours" value="{!! $performer->working_hours !!}">
    </p>

    <div class="border mt-3 mb-3 p-2">
        <h4>Филиалы:</h4>
        <hr>
        <div class="branches">
            @foreach($performer->branches()->get() as $branch)
                <div class="pt-1 pb-1" id="branch-{{ $branch->id }}">
                    <input type="text" disabled class="form-control select mr-1 branch-name" style="max-width: calc(100% - 200px)" value="{{ $branch->address }} {{ $branch->name }}">
                    <input type="hidden" class="branch" value="{{ $branch->id }}">
                    <button class="btn btn-primary btn-branch-edit mr-1">Изменить</button>
                    <button class="btn btn-primary btn-branch-delete">Удалить</button>
                </div>
            @endforeach
        </div>
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

    <button type="button" class="btn btn-primary btn-performer-save">Сохранить изменения</button>

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
    @endif
@endsection

@section('scripts')
    @if(\App\Functions::is_admin())
        <script>
            $(document).ready(function(){
                tinymce.init({
                    selector: '#presentation',
                    <?php echo \App\Settings::$tinymce_settings; ?>
                });

                reset_special_handlers();

                $('.btn-performer-photo-choose').click(function(e){
                    $('.photo-performer-add').click();
                });
                $('.photo-performer-add').change(function(){
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
                                        $('.img-performer-photo').removeClass('d-none');
                                        $('.img-performer-photo').attr('src', '/images/performers/'+data.file_name+'?t='+(new Date).getTime());
                                        $('.performer-edit-input[name=photo]').val(data.file_name);
                                    }
                                }
                            },
                            error: function(xhr){
                                $('.img-performer-photo').addClass('d-none');
                                $('.performer-edit-input[name=photo]').val('null');
                                display_error(xhr);
                            }
                        });
                    }
                });

                $('.select-branch-add').change(function(){
                    if ($(this).val() == 'add'){
                        $('#BranchAddModal').modal('show');
                    } else {
                        $('.btn-branch-add').click();
                    }
                });
                $('.btn-branch-add').click(function(){
                    if (!$(this).hasClass('btn-save')){
                        let ok = true;
                        $('.branch').each(function (index, element) {
                            if ($(element).val().trim() == $('.select-branch-add').val().trim()) {
                                ok = false;
                            }
                        });
                        if (ok){
                            $('.branches').append(
                                '<div class="pt-1 pb-1" id="branch-'+$('.select-branch-add').val()+'">' +
                                '<input type="text" disabled class="form-control select mr-1 branch-name" style="max-width: calc(100% - 200px)" value="' + $('.select-branch-add').children('option:selected').text().trim() + '">' +
                                '<input type="hidden" class="branch" value="' + $('.select-branch-add').val() + '">' +
                                '<button class="btn btn-primary btn-branch-edit mr-1">Изменить</button>' +
                                '<button class="btn btn-primary btn-branch-delete">Удалить</button>' +
                                '</div>'
                            );
                            reset_special_handlers();
                        }
                    }
                    if ($(this).hasClass('btn-save')){
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
                                            $('.select-branch-add').val('null');
                                            $('.select-branch-add').append(
                                                '<option selected value="' + data.id + '">' +
                                                $('.branch-add-input[name=address]').val().trim() + ' ' + $('.branch-add-input[name=name]').val().trim() +
                                                '</option>'
                                            );
                                            $('#BranchAddModal').modal('hide');
                                            $('.branch-add-input').each(function (index, element) {
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

                $('.btn-performer-save').click(function(){
                    tinymce.get('presentation').save();

                    let branches = [];
                    $('.branch').each(function (index, element) {
                        if ($(element).val().trim() != '')
                            branches.push($(element).val().trim());
                    });
                    $('[name=branches]').val(JSON.stringify(branches));

                    let data = new FormData();
                    $('.performer-edit-input').each(function(i, elem){
                        data.append($(elem).attr('name'), $(elem).val());
                    });
                    $.ajax({
                        url: '/api/performer/{{ $performer->id }}/edit',
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(data, status, xhr){
                            if (status == 'success'){
                                if (data.status == 'success'){
                                    window.location.reload();
                                    //window.location.assign('/performers');
                                }
                            }
                        },
                        error: function(xhr){
                            display_error(xhr);
                        }
                    });
                });
            });

            function reset_special_handlers(){
                $('.btn-branch-edit').unbind('click');
                $('.btn-branch-edit').click(function(){
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
                                            $('.select-branch-add').children('option').each(function (index, element) {
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

                $('.btn-branch-delete').unbind('click');
                $('.btn-branch-delete').click(function(){
                    $(this).parent().remove();
                });
            }
        </script>
    @endif
@endsection
