@extends('layout')

@section('title')
Стандарты работы - {{ \App\Settings::$title_site_name }} @endsection

@section('body')
    @if (count(\App\Models\articles_sections::where('parent_section_id', null)->get()) == 0 && count(\App\Models\articles::where('section_id', null)->get()) == 0)
        <h3>Стандартов нет</h3>
    @else
        <h3>Стандарты работы</h3>
        <div class="articles_list">
            {{ echo_articles_list(\App\Models\articles_sections::where('parent_section_id', null)->orderBy('name', 'asc')->get()) }}
            {{ echo_articles_list(\App\Models\articles::where('section_id', null)->orderBy('name', 'asc')->get()) }}
        </div>
    @endif

    @if(\App\Functions::is_admin())
    <button class="btn btn-primary btn-article-new" data-toggle="modal" data-target="#ArticleNewModal">Добавить статью</button>
    <button class="btn btn-primary btn-section-new" data-toggle="modal" data-target="#SectionNewModal">Добавить раздел</button>

    <!-- Создание статьи -->
    <div class="modal fade" id="ArticleNewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Создать статью</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        <b>Заголовок:</b><br>
                        <input type="text" name="name" class="form-control article-new-input">
                    </p>
                    <p>
                        <b>Раздел:</b><br>
                        <select name="section_id" class="form-control article-new-input">
                            <option value="null" disabled selected>Выбрать...</option>
                            @foreach(\App\Models\articles_sections::all() as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </p>

                    <textarea name="content" id="article-new-content" class="article-new-input"></textarea>

                    <div class="border mt-3 mb-3 p-2">
                        <h4>Доступные изображения:</h4>
                        <hr>
                        <div id="images" class="d-grid gtc-3"></div>
                        <hr>
                        <button class="btn btn-outline-primary d-inline-block w-25 btn-upload">Загрузить файл</button>
                        <input type="text" class="form-control d-inline-block align-middle input-image-name" style="width: calc(75% - 4.5px)" placeholder="Название изображения (обязательно)">
                        <div class="mt-1 mb-0 alert-warning alert">Изображение с данным именем уже существует, оно будет перезаписано.</div>
                        <button class="btn btn-primary mt-1 btn-image-add">Добавить</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary btn-save btn-article-new">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Создание раздела -->
    <div class="modal fade" id="SectionNewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Создать раздел</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        <b>Название:</b><br>
                        <input type="text" name="name" class="form-control section-new-input">
                    </p>
                    <p>
                        <b>Раздел:</b><br>
                        <select name="parent_section_id" class="form-control section-new-input">
                            <option value="null" disabled selected>Выбрать...</option>
                            @foreach(\App\Models\articles_sections::all() as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary btn-save btn-section-new">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Изменение раздела -->
    <div class="modal fade" id="SectionEditModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Редактировать раздел</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" class="form-control section-edit-input">
                    <p>
                        <b>Название:</b><br>
                        <input type="text" name="name" class="form-control section-edit-input">
                    </p>
                    <p>
                        <b>Раздел:</b><br>
                        <select name="parent_section_id" class="form-control section-edit-input">
                            <option value="null" disabled selected>Выбрать...</option>
                            @foreach(\App\Models\articles_sections::all() as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary btn-save btn-section-edit">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Подтвердить удаление -->
    <div class="modal fade" id="ItemDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Удаление элемента</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>Вы действительно хотите удалить данный элемент?</h3>
                    <input type="hidden" class="item-delete-input" name="id">
                    <input type="hidden" class="item-delete-input" name="type">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-danger btn-save btn-item-delete">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Вывод адреса файла -->
    <div class="modal fade" id="FileAddressModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Файл</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Изображение:
                        <input type="text" class="form-control file-name-input" disabled>
                    </p>
                    <p>
                        Ссылка:
                        <input type="text" class="form-control file-address-input">
                    </p>
                    <p class="copied">
                        Скопировано!
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">ОК</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
    @if(\App\Functions::is_admin())
    <script>
        let selected_sections = [];
        let selected_articles = [];
        let upload_elem;
        $(document).ready(function(){
            tinymce.init({
                selector: '#article-new-content',
                <?php echo \App\Settings::$tinymce_settings; ?>
            });
            $('.input-image-name').parent().children('.alert-warning').slideUp(0);
            $('.copied').slideUp(0);
            load_media_library();

            $('.btn-upload').click(function(){
                $(upload_elem).remove();
                upload_elem = $('<input type="file" class="d-none" accept="image/*">');
                $('body').append(upload_elem);
                upload_elem.click();
                upload_elem = upload_elem[0];
                reset_special_handlers();
            });
            $('.input-image-name').on('input', function(e){
                $.ajax({
                    url: '/api/media/exist/'+$('.input-image-name').val().trim(),
                    method: 'get',
                    data: null,
                    processData: false,
                    contentType: false,
                    success: function (data, status, xhr) {
                        if (status == 'success') {
                            if (data.status == 'exist') {
                                $('.input-image-name').parent().children('.alert-warning').slideDown(200);
                            } else if (data.status == 'not exist'){
                                $('.input-image-name').parent().children('.alert-warning').slideUp(200);
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
            $('.btn-image-add').click(function () {
                if ($('.input-image-name').val().trim() != ''){
                    let data = new FormData();
                    data.append('upload', upload_elem.files[0]);
                    $.ajax({
                        url: '/api/upload/articles/'+$('.input-image-name').val().trim(),
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function (data, status, xhr) {
                            if (status == 'success') {
                                if (data.status == 'success') {
                                    $(upload_elem).remove();
                                    $('.btn-upload').text('Загрузить файл');
                                    $('.input-image-name').val('');
                                    $('.input-image-name').parent().children('.alert-warning').slideUp(200);
                                    load_media_library();
                                    reset_special_handlers();
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
            });

            $('.file-address-input').on('focus', function (e) {
                setTimeout(function(){
                    $('.file-address-input').select();
                    document.execCommand('copy');
                    $('.copied').slideDown(200);
                    setTimeout(function () {
                        $('.copied').slideUp(200);
                    }, 2000);
                }, 750);
            });

            $('.article-list-item').on('mouseenter', function () {
                $('.article-list-item').each(function(i, elem){
                    $(elem).children('.article-item-admin-buttons').removeClass('d-inline-block');
                    $(elem).children('.article-item-admin-buttons').addClass('d-none');
                });
                $(this).children('.article-item-admin-buttons').removeClass('d-none');
                $(this).children('.article-item-admin-buttons').addClass('d-inline-block');
            });

            $('.btn-article-new').click(function(){
                if ($(this).hasClass('btn-save')) {
                    tinymce.get('article-new-content').save();
                    if ($('.article-new-name').val() != '' &&
                        $('.article-new-content').val() != '') {
                        let data = new FormData();
                        $('.article-new-input').each(function (i, elem) {
                            data.append($(elem).attr('name'), $(elem).val());
                        });
                        $.ajax({
                            url: '/api/article/add',
                            method: 'post',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (data, status, xhr) {
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
                    } else {
                        display_error('<p>Заполните оба поля</p>');
                    }
                }
            });

            $('.btn-section-new').click(function(){
                if ($(this).hasClass('btn-save')) {
                    if ($('.section-new-name').val() != '') {
                        let data = new FormData();
                        $('.section-new-input').each(function (i, elem) {
                            data.append($(elem).attr('name'), $(elem).val());
                        });
                        $.ajax({
                            url: '/api/article_section/add',
                            method: 'post',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (data, status, xhr) {
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
                    } else {
                        display_error('<p>Введите название раздела</p>');
                    }
                }
            });

            $('.btn-section-edit').click(function(e){
                if ($(this).hasClass('btn-save')) {
                    if ($('.section-edit-name').val() != '') {
                        let data = new FormData();
                        let id;
                        $('.section-edit-input').each(function (i, elem) {
                            if ($(elem).attr('name') == 'id')
                                id = $(elem).val();
                            else
                                data.append($(elem).attr('name'), $(elem).val());
                        });
                        $.ajax({
                            url: '/api/article_section/'+id+'/edit',
                            method: 'post',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (data, status, xhr) {
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
                    } else {
                        display_error('<p>Введите название раздела</p>');
                    }
                } else if (!$(this).hasClass('btn-save')) {
                    e.stopPropagation();
                    e.preventDefault();
                    let classes = $(this).parent()[0].className.split(' ');
                    classes.forEach(function (elem, i, arr) {
                        if (elem.indexOf('section-') == 0 && elem.split('-').length == 2) {
                            $.ajax({
                                url: '/api/article_section/' + elem.split('-')[1] + '/get',
                                method: 'get',
                                data: null,
                                processData: false,
                                contentType: false,
                                success: function (data, status, xhr) {
                                    if (status == 'success') {
                                        if (data.status == 'found') {
                                            $('#SectionEditModal').find('.section-edit-input[name=id]').val(data.object.id);
                                            $('#SectionEditModal').find('.section-edit-input[name=name]').val(data.object.name);
                                            $('#SectionEditModal').find('.section-edit-input[name=parent_section_id]').val(data.object.parent_section_id);
                                            $('#SectionEditModal').find('.section-edit-input[name=content]').val(data.object.content);
                                            $('#SectionEditModal').modal('show');
                                        }
                                    }
                                },
                                error: function (xhr) {
                                    display_error(xhr);

                                }
                            });
                        }
                    });
                }
            });

            $('.section-edit-input[name=parent_section_id]').change(function(){
                if ($('.section-edit-input[name=id]').val() == $(this).val()){
                    $(this).val('null');
                }
            });

            $('.btn-article-edit').click(function(e){
                e.stopPropagation();
                e.preventDefault();
                let classes = $(this).parent()[0].className.split(' ');
                classes.forEach(function(elem, i, arr){
                    if (elem.indexOf('article-') == 0 && elem.split('-').length == 2){
                        let addr = '/article/' + elem.split('-')[1] + '/edit';
                        window.location.assign(addr);
                    }
                });
            });

            $('.btn-article-delete').click(function(e){
                e.stopPropagation();
                e.preventDefault();
                let classes = $(this).parent()[0].className.split(' ');
                classes.forEach(function(elem, i, arr){
                    if (elem.indexOf('article-') == 0 && elem.split('-').length == 2){
                        $('.item-delete-input[name=type]').val('article');
                        $('.item-delete-input[name=id]').val(elem.split('-')[1]);
                        $('#ItemDeleteModal').modal('show');
                    }
                });
            });

            $('.btn-section-delete').click(function(e){
                e.stopPropagation();
                e.preventDefault();
                let classes = $(this).parent()[0].className.split(' ');
                classes.forEach(function(elem, i, arr){
                    if (elem.indexOf('section-') == 0 && elem.split('-').length == 2){
                        $('.item-delete-input[name=type]').val('article_section');
                        $('.item-delete-input[name=id]').val(elem.split('-')[1]);
                        $('#ItemDeleteModal').modal('show');
                    }
                });
            });

            $('.btn-item-delete').click(function(){
                as_delete($('.item-delete-input[name=type]').val(), $('.item-delete-input[name=id]').val());
            });
        });

        function as_delete(type, id){
            $.ajax({
                url: '/api/'+type+'/'+id+'/delete',
                method: 'get',
                data: null,
                processData: false,
                contentType: false,
                success: function (data, status, xhr) {
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
        }

        function reset_special_handlers(){
            $(upload_elem).unbind('change');
            $(upload_elem).change(function(e){
                if (upload_elem.files.length > 0) {
                    $('.btn-upload').text('Файл "'+upload_elem.files[0].name+'"');
                }
            });

            $('.image').unbind('click');
            $('.image').click(function(){
                $('.file-name-input').val($(this)[0].dataset.imageName);
                $('.file-address-input').val($(this).prop('src').split('?')[0]);
                $('#FileAddressModal').modal('show');
                $('.file-address-input').focus();
            });
        }

        function load_media_library(){
            $('#images').html('');
            $.ajax({
                url: '/api/media/get/all',
                method: 'get',
                data: null,
                processData: false,
                contentType: false,
                success: function (data, status, xhr) {
                    if (status == 'success') {
                        if (data.status == 'success') {
                            data.objects.forEach(function(value, index, array){
                                $('#images').append('<div class="p-1" style="cursor: pointer;"><img src="/images/articles/'+value.file_name+'?t='+(new Date()).getTime()+'" class="image" data-image-name="'+value.name+'"><p>'+value.name+'</p></div>');
                            });
                            reset_special_handlers();
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
    </script>
    @endif
@endsection
<?php
function echo_articles_list($list, $top = true){
    if(count($list) == 0) return;

    if (!$top) echo '<img src="/images/icons/arrow_down.png" class="open_arrow">';

    if(\App\Functions::is_admin() && false){
        echo '<table class="table '.( get_class($list[0]) == 'App\Models\articles' ? 'articles' : 'sections' ).'" style="'.( $top ? 'display: block;' : 'display: none;' ).'"><tbody>';
        echo '<tr><th width="30"><input type="checkbox" class="select-all-checkbox"></th><th></th></tr>';
    } else {
        echo '<ul style="'.( $top ? 'display: block;' : 'display: none;' ).'">';
    }

    foreach ($list as $item){
        if ( get_class($item) == 'App\Models\articles' ||
            (get_class($item) == 'App\Models\articles_sections' &&
                (( $top && count($item->parent()->get()) == 0) ||
                 (!$top && count($item->parent()->get()) != 0)))) {

            if (\App\Functions::is_admin() && false){
                echo '<tr class="'.( get_class($item) == 'App\Models\articles' ? 'article-'.$item->id : 'section-'.$item->id ).'"><td><input type="checkbox" class="select-one-checkbox"></td><td class="border article-list-item">';
            } else {
                echo '<li class="article-list-item">';
            }

            if (get_class($item) == 'App\Models\articles')
                echo '<div class="d-inline-block"><span style="vertical-align: top;"><b><a href="/article/'.$item->id.'">'.$item->name.'</a></b></span></div>';
            if (get_class($item) == 'App\Models\articles_sections')
                echo '<div class="d-inline-block"><span style="vertical-align: top;">'.$item->name.'</span></div>';

            if (\App\Functions::is_admin()){
                echo '<div class="ml-2 article-item-admin-buttons d-none '.( get_class($item) == 'App\Models\articles' ? 'article' : 'section' ).'-'.$item->id.'" style="max-height: 30px; height: 30px;"><button class="mr-1 border-0 bg-white btn-'.( get_class($item) == 'App\Models\articles' ? 'article' : 'section' ).'-edit" title="Изменить">
                    <img src="/images/icons/edit.svg">
                </button><button class="border-0 bg-white btn-'.( get_class($item) == 'App\Models\articles' ? 'article' : 'section' ).'-delete" title="Удалить" type="button" data-toggle="modal" data-target="#SectionDeleteModal">
                    <img src="/images/icons/trash.svg">
                </button></div>';
            }

            if (get_class($item) == 'App\Models\articles_sections' && count($item->children()->get()) != 0) {
                echo_articles_list($item->children()->orderBy('name', 'asc')->get(), false);
            }
            if (get_class($item) == 'App\Models\articles_sections' && count($item->articles()->get()) != 0) {
                echo_articles_list($item->articles()->orderBy('name', 'asc')->get(), false);
            }

            if (\App\Functions::is_admin() && false)
                echo '</td></tr>';
            else
                echo '</li>';
        }
    }

    if(\App\Functions::is_admin() && false)
        echo '</tbody></table>';
    else
        echo '</ul>';

}

?>
