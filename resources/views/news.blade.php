@extends('layout')

@section('title')
Новости - {{ \App\Settings::$title_site_name }} @endsection

@section('styles')
    <style>
        .table td {
            text-align: left;
        }
    </style>
@endsection

@section('body')
    <?php
    $news = \App\Models\news::orderBy('is_important', 'desc')->paginate(\App\Settings::$posts_per_page);
    if (count($news) == 0){
        echo '<h3>Нет новостей</h3>';
        if (\App\Functions::is_admin()){
            ?>
            <button class="btn btn-primary btn-news-new" data-toggle="modal" data-target="#NewsNewModal">Добавить Новость</button>
            <?php
        }
    } else {?>
        <h3>Новости</h3>

        <div class="alert alert-success" role="alert" style="display: none;"></div>
        <div class="alert alert-danger" role="alert" style="display: none;"></div>
        <table class="table" style="table-layout: auto">
            <tbody>
            <tr>
                @if(\App\Functions::is_admin())<th width="30"><input type="checkbox" class="select-all-checkbox"></th>@endif
                <th>Новость</th>
                <th>Время</th>
            </tr>
            @foreach(\App\Models\news::where('is_important', 1)->orderBy('updated_at', 'desc')->paginate(\App\Settings::$posts_per_page) as $new)
                <tr class="news-{{ $new->id }}">
                    @if(\App\Functions::is_admin())<td><input type="checkbox" class="select-one-checkbox"></td>@endif
                    <td>
                        <p><b>@if($new->is_important)<span class="important">{{ $new->title }}</span>@else{{ $new->title }}@endif</b></p>
                        <p>{!! $new->content !!}</p>
                    </td>
                    <td>{{ date('d.m.Y H:i:s', strtotime($new->updated_at) + 5*60*60) }}</td>
                </tr>
            @endforeach
            @foreach(\App\Models\news::where('is_important', 0)->orderBy('updated_at', 'desc')->paginate(\App\Settings::$posts_per_page) as $new)
                <tr class="news-{{ $new->id }}">
                    @if(\App\Functions::is_admin())<td><input type="checkbox" class="select-one-checkbox"></td>@endif
                    <td>
                        <p><b>@if($new->is_important)<span class="important">{{ $new->title }}</span>@else{{ $new->title }}@endif</b></p>
                        <p>{!! $new->content !!}</p>
                    </td>
                    <td>{{ date('d.m.Y H:i:s', strtotime($new->updated_at) + 5*60*60) }}</td>
                </tr>
            @endforeach
            @if(\App\Functions::is_admin())
                <tr>
                    <td colspan="3">
                        С выбранными:
                        <select class="form-control news-status-select" style="max-width: fit-content; display: inline-block; vertical-align: middle;">
                            <option value="-1" selected disabled>Установить статус...</option>
                            <option value="0">Установить статус Обычная новость</option>
                            <option value="1">Установить статус Важная новость</option>
                        </select>
                        <button class="btn btn-danger btn-news-delete" data-toggle="modal" data-target="#NewsDeleteModal">Удалить</button>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="align-center"><button class="btn btn-primary btn-news-new" data-toggle="modal" data-target="#NewsNewModal">Добавить Новость</button></td>
                </tr>
            @endif
            </tbody>
        </table>
        {{ $news->links('vendor.pagination.default') }}

    <?php
    }
    ?>

    @if(\App\Functions::is_admin())
    <!-- Подтвердить удаление новости -->
    <div class="modal fade" id="NewsDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Удалить выбранные новости</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>Вы действительно хотите удалить выбранные новости?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-danger btn-save btn-news-delete">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Создание новости -->
    <div class="modal fade" id="NewsNewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Создать новость</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger news-new-modal-danger" role="alert" style="display: none;"></div>
                    <p>
                        <b>Заголовок:</b><br>
                        <input type="text" name="title" id="news-new-title" class="form-control">
                    </p>
                    <p>
                        <b>Содержание:</b><br>
                        <textarea type="text" name="content" id="news-new-content" class="form-control"></textarea>
                    </p>
                    <p>
                        <label for="news-new-is-important"><input type="checkbox" name="is_important" id="news-new-is-important" class="form-control d-inline-block"> Важная</label>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary btn-save btn-news-new">Подтвердить</button>
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
                selector: '#news-new-content',
                <?php echo \App\Settings::$tinymce_settings; ?>
            });

            $('.btn-news-delete').click(function (e) {
                if ($(this).hasClass('btn-save')) {
                    $('#NewsDeleteModal').modal('hide');
                    selected_news.forEach(function (value, index, array) {
                        $.ajax({
                            url: '/api/news/' + value + '/delete',
                            method: 'get',
                            data: null,
                            processData: false,
                            contentType: false,
                            success: function (data, status, xhr) {
                                if (status == 'success') {
                                    if (data.status == 'success') {
                                        array.splice(index, 1);
                                        if (array.length == 0)
                                            window.location.reload();
                                    } else if (data.status == 'error') {
                                        $('.alert-danger').html(data.message);
                                        $('.alert-danger').slideDown(300);
                                    } else {
                                        display_error(xhr);
                                    }
                                }
                            },
                            error: function(xhr){
                                display_error(xhr);

                            }
                        });
                    });
                }
            });

            $('.btn-news-new').click(function(){
                if ($(this).hasClass('btn-save') &&
                    $('#news-new-title').val().trim() != ''){
                    tinymce.get('news-new-content').save();
                    let data = new FormData();
                    data.append($('#news-new-title').attr('name'), $('#news-new-title').val());
                    data.append($('#news-new-content').attr('name'), $('#news-new-content').val());
                    if ($('#news-new-is-important').prop('checked'))
                        data.append($('#news-new-is-important').attr('name'), '1');
                    else
                        data.append($('#news-new-is-important').attr('name'), '0');
                    $.ajax({
                        url: '/api/news/add',
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function (data, status, xhr) {
                            console.log([data, status, xhr]);
                            if (data.status == 'success') {
                                window.location.reload();
                            } else if (data.status == 'error'){
                                $('.news-new-modal-danger').html(data.message);
                                $('.news-new-modal-danger').slideDown(300);
                            } else {
                                display_error(xhr);
                            }
                        },
                        error: function(xhr, status, error){
                            console.log([xhr, status, error]);
                        }
                    });
                }
            });

            let selected_news = [];
            $('.news-status-select').on('change', function(e){
                if ($(e.target).val() == -1) return;
                selected_news.forEach(function(value, index, array){
                    let id = value;
                    let data = new FormData();
                    data.append('field', 'is_important');
                    data.append('value', $(e.target).val());
                    $.ajax({
                        url: '/api/news/'+id+'/edit_field',
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(data, status, xhr){
                            console.log([data, status, xhr]);
                            if (data.status == 'success') {
                                $('.alert-success').append('<p>Статус новости '+id+' изменен</p>');
                                $('.alert-success').slideDown(300);
                                setTimeout(function(){
                                    $('.alert-success').slideUp(300);
                                    $('.alert-success').html('');
                                    window.location.reload();
                                }, 3000);
                            } else if (data.status == 'error'){
                                $('.alert-danger').html(data.message);
                                $('.alert-danger').slideDown(300);
                            } else {
                                display_error(xhr);
                            }
                        },
                        error: function(xhr){
                            display_error(xhr);

                        }
                    });
                });
            });

            $('.select-all-checkbox').change(function (e) {
                $('.select-one-checkbox').prop('checked', $(this).prop('checked'));
                if ($(this).prop('checked')){
                    $('tr').each(function(index, elem){
                        if (elem.className.indexOf('news-') != -1){
                            let id = -1;
                            let classes = elem.className.split(' ');
                            classes.forEach(function(value, index, array){
                                if (value.split('-').length == 2){
                                    if (value.split('-')[0] == 'news'){
                                        id = value.split('-')[1];
                                    }
                                }
                            });
                            if (selected_news.indexOf(id) == -1)
                                selected_news.push(id);
                        }
                    });
                } else {
                    selected_news = [];
                }
            });

            $('.select-one-checkbox').change(function (e) {
                let id = -1;
                let classes = $(this).parent().parent()[0].className.split(' ');
                classes.forEach(function(value, index, array){
                    if (value.split('-').length == 2){
                        if (value.split('-')[0] == 'news'){
                            id = value.split('-')[1];
                        }
                    }
                });
                if ($(this).prop('checked') && selected_news.indexOf(id) == -1){
                    selected_news.push(id);
                } else {
                    selected_news.forEach(function(value, index, array){
                        if (value == id){
                            array.splice(index, 1);
                        }
                    });
                }
            });

            @if(\App\Functions::is_admin())
            $('.table td').click(function(e){
                if (e.target.nodeName != 'INPUT')
                    $(this).parent().find(':first-child').find('input[type=checkbox]').click();
            });
            @endif
        });
    </script>
    @endif
@endsection


