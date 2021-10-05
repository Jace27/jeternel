@extends('layout')

<?php
$article_exist = true;
$article = \App\Models\articles::where('id', $id)->first();
if ($article == null){
    $article = new \App\Models\articles();
    $article->name = "Статьи не существует";
    $article->section_id = -1;
    $article_exist = false;
}
?>

@section('title')
{{ $article->name }} - {{ \App\Settings::$title_site_name }} @endsection

@section('body')
    @if(!$article_exist)
        <h3>{{ $article->name }}</h3>
    @else
        <h3>Редактировать статью</h3>
        <input type="text" name="name" class="form-control input-article-edit" value="{{ $article->name }}">
        <br>
        <select name="section_id" class="form-control input-article-edit">
            <option value="null" disabled selected>Выбрать...</option>
            @foreach(\App\Models\articles_sections::all() as $section)
                <option value="{{ $section->id }}" @if($section->id == $article->section_id) selected @endif>{{ $section->name }}</option>
            @endforeach
        </select>
        <br>
        <textarea name="content" id="content" class="input-article-edit">{{ $article->content }}</textarea>
        <br>
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
        <br>
        <button class="btn btn-primary btn-article-save">Сохранить изменения</button>
    @endif

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
@endsection

@section('scripts')
    <script>
        let upload_elem;
        $(document).ready(function(){
            tinymce.init({
                selector: '#content',
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

            $('.btn-article-save').click(function(){
                tinymce.get('content').save();
                if ($('.input-article-edit[name=name]').val().trim() != '' &&
                    $('.input-article-edit[name=content]').val().trim() != ''){
                    let data = new FormData();
                    $('.input-article-edit').each(function(i, elem){
                        data.append($(elem).attr('name'), $(elem).val());
                    });
                    $.ajax({
                        url: '/api/article/{{ $article->id }}/edit',
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(data, status, xhr){
                            if (status == 'success'){
                                if (data.status == 'success'){
                                    window.location.assign('/article/{{ $article->id }}');
                                }
                            }
                        },
                        error: function(xhr){
                            if (xhr.responseJSON != null){
                                if (xhr.responseJSON.message != null)
                                    display_error(xhr.responseJSON.message);
                                else
                                    display_error(xhr.responseText);
                            } else {
                                display_error(xhr.responseText);
                            }
                            $('#ErrorModal').modal('show');
                        }
                    });
                }
            });
        });

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
                $('.file-address-input').focus();
                $('#FileAddressModal').modal('show');
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
@endsection
