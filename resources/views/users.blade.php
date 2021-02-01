@extends('layout')

@section('title')
Пользователи - База знаний Jeternel @endsection

@section('styles')
    <style>
        .table td {
            text-align: left;
        }
    </style>
@endsection

@section('body')
    <?php
    $users = \App\Models\users::all();
    if (count($users) == 0){
        echo '<h3>Нет пользователей</h3>';
    } else {
        $roles = \App\Models\roles::all();
    ?>
    <h3>Пользователи</h3>
    <div class="alert alert-success" role="alert" style="display: none;"></div>
    <div class="alert alert-danger" role="alert" style="display: none;"></div>
    <table class="table">
        <tbody>
        <tr>
            <th><input type="checkbox" class="select-all-checkbox"></th>
            <th>Телефон</th>
            <th>Фамилия</th>
            <th>Имя</th>
            <th>Отчество</th>
            <th>Статус</th>
        </tr>
        @foreach ($users as $user)
            <tr class="user-{{ $user->id }}">
                <td><input type="checkbox" class="select-one-checkbox"></td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->third_name }}</td>
                <td>
                    <select class="form-control user-role-select" style="width: 173px;">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" @if($role->id == $user->role()->first()->id) selected @endif>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6">
                С выбранными:
                <button class="btn btn-danger btn-user-discard-password">Сбросить пароль</button>
                <button class="btn btn-danger btn-user-delete" data-toggle="modal" data-target="#UserDeleteModal">Удалить</button>
            </td>
        </tr>
        <tr>
            <td colspan="6" class="align-center"><button class="btn btn-primary btn-user-new" data-toggle="modal" data-target="#UserNewModal">Добавить пользователя</button></td>
        </tr>
        </tbody>
    </table>
    <?php
    }
    ?>

    <!-- Создание пользователя -->
    <div class="modal fade" id="UserNewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Создать пользователя</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger user-new-modal-danger" role="alert" style="display: none;"></div>
                    <p>
                        <b>Телефон:</b><br>
                        <input type="text" name="phone" id="user-new-phone" class="form-control">
                    </p>
                    <p>
                        <b>Фамилия:</b><br>
                        <input type="text" name="last_name" id="user-new-last-name" class="form-control">
                    </p>
                    <p>
                        <b>Имя:</b><br>
                        <input type="text" name="first_name" id="user-new-first-name" class="form-control">
                    </p>
                    <p>
                        <b>Отчество:</b><br>
                        <input type="text" name="third_name" id="user-new-third-name" class="form-control">
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary btn-save btn-user-new">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Подтвердить удаление пользователя -->
    <div class="modal fade" id="UserDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Удалить выбранных пользователей</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>Вы действительно хотите удалить выбранных пользователей?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-danger btn-save btn-user-delete">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            $('.btn-user-discard-password').click(function (e) {
                let data = new FormData();
                data.append('field', 'password');
                data.append('value', 'null');
                selected_users.forEach(function(value, index, array){
                    $.ajax({
                        url: '/api/user/'+value+'/edit_field',
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(data, status, xhr){
                            console.log([data, status, xhr]);
                            if (data.status == 'success') {
                                $('.alert-success').html('Пароль сброшен');
                                $('.alert-success').slideDown(300);
                                setTimeout(function(){
                                    $('.alert-success').slideUp(300);
                                    $('.alert-success').html('');
                                }, 3000);
                            } else if (data.status == 'error'){
                                $('.alert-danger').html(data.message);
                                $('.alert-danger').slideDown(300);
                            }
                        }
                    });
                });
            });

            $('.btn-user-delete').click(function (e) {
                if ($(this).hasClass('btn-save')) {
                    $('#UserDeleteModal').modal('hide');
                    selected_users.forEach(function (value, index, array) {
                        $.ajax({
                            url: '/api/user/' + value + '/delete',
                            method: 'get',
                            data: null,
                            processData: false,
                            contentType: false,
                            success: function (data, status, xhr) {
                                console.log([data, status, xhr]);
                                if (data.status == 'success') {
                                    if (array.length == 0)
                                        window.location.reload();
                                } else if (data.status == 'error') {
                                    $('.alert-danger').html(data.message);
                                    $('.alert-danger').slideDown(300);
                                }
                            }
                        });
                        array.splice(index, 1);
                    });
                }
            });

            $('.user-role-select').on('change', function(e){
                let user_id = $(this).parent().parent()[0].className.split('-')[1];
                let data = new FormData();
                data.append('field', 'role_id');
                data.append('value', $(this).val());
                $.ajax({
                    url: '/api/user/'+user_id+'/edit_field',
                    method: 'post',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data, status, xhr){
                        console.log([data, status, xhr]);
                        if (data.status == 'success') {
                            $('.alert-success').html('Статус пользователя изменен');
                            $('.alert-success').slideDown(300);
                            setTimeout(function(){
                                $('.alert-success').slideUp(300);
                                $('.alert-success').html('');
                            }, 3000);
                        } else if (data.status == 'error'){
                            $('.alert-danger').html(data.message);
                            $('.alert-danger').slideDown(300);
                        }
                    }
                });
            });

            $('.select-all-checkbox').change(function (e) {
                $('.select-one-checkbox').prop('checked', $(this).prop('checked'));
                if ($(this).prop('checked')){
                    $('tr').each(function(index, elem){
                        if (elem.className.indexOf('user-') != -1){
                            if (selected_users.indexOf(elem.className.split('-')[1]) == -1)
                                selected_users.push(elem.className.split('-')[1]);
                        }
                    });
                } else {
                    selected_users = [];
                }
            });

            let selected_users = [];
            $('.select-one-checkbox').change(function (e) {
                let user_id = $(this).parent().parent()[0].className.split('-')[1];
                if ($(this).prop('checked') && selected_users.indexOf(user_id) == -1){
                    selected_users.push(user_id);
                } else {
                    selected_users.forEach(function(value, index, array){
                        if (value == user_id){
                            array.splice(index, 1);
                        }
                    });
                }
            });

            let phone_status = 'unknown';
            $('#user-new-phone').on('input', function () {
                let data = new FormData();
                data.append('phone', $(this).val().trim());
                $.ajax({
                    url: '/api/check_phone',
                    method: 'post',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data, status, xhr){
                        if (status == 'success') {
                            phone_status = data.status;
                            switch (data.status) {
                                case 'user does not exist':
                                    $('.user-new-modal-danger').html('');
                                    $('.user-new-modal-danger').slideUp(300);
                                    break;
                                case 'new user':
                                    $('.user-new-modal-danger').html('');
                                    $('.user-new-modal-danger').slideUp(300);
                                    break;
                                case 'user exist':
                                    $('.user-new-modal-danger').html('Пользователь существует');
                                    $('.user-new-modal-danger').slideDown(300);
                                    break;
                            }
                        } else {
                            console.log(xhr);
                            $('.user-new-modal-danger').html('Не удалось проверить статус пользователя');
                            $('.user-new-modal-danger').slideDown(300);
                        }
                    }
                });
            });

            $('.btn-user-new').click(function(){
                if ($(this).hasClass('btn-save') &&
                    $('#user-new-phone').val().trim() != '' &&
                    phone_status != 'user exist' &&
                    phone_status != 'unknown'){
                    let data = new FormData();
                    data.append($('#user-new-phone').attr('name'), $('#user-new-phone').val());
                    data.append($('#user-new-last-name').attr('name'), $('#user-new-last-name').val());
                    data.append($('#user-new-first-name').attr('name'), $('#user-new-first-name').val());
                    data.append($('#user-new-third-name').attr('name'), $('#user-new-third-name').val());
                    $.ajax({
                        url: '/api/user/add',
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function (data, status, xhr) {
                            console.log([data, status, xhr]);
                            if (data.status == 'success') {
                                window.location.reload();
                            } else if (data.status == 'error'){

                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
