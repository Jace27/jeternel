@extends('layout')

<?php
if (!isset($mode)) $mode = 'view';
?>

@section('title')
Акции - {{ \App\Settings::$title_site_name }} @endsection

@section('body')
    <div id="tabs">
        <ul>
            <li><a href="#prom_type_all">Общие</a></li>
            <li><a href="#prom_type_a">Категория A</a></li>
            <li><a href="#prom_type_b">Категория B</a></li>
            <li><a href="#prom_type_c">Категория C</a></li>
        </ul>
        <div id="prom_type_all">
            @php
                $promotions = \App\Models\promotions::orderBy('title');
                $promotions_collection = $promotions->paginate(\App\Settings::$posts_per_page);
                foreach ($promotions_collection as $key => $promotion){
                    if (($promotion->start != null && strtotime($promotion->start) > time()) ||
                        ($promotion->end != null && strtotime($promotion->end) < time()))
                	    unset($promotions_collection[$key]);
                }
            @endphp
            @if(count($promotions_collection) == 0)
                <h3>Нет акций</h3>
            @else
                @if(\App\Functions::is_admin())
                <table class="table promotions">
                    <tbody>
                    <tr>
                        <th width="30"><input type="checkbox" class="select-all-checkbox"></th>
                        <th>Акция</th>
                        <th></th>
                    </tr> @endif
                    @foreach($promotions_collection as $key => $promotion)
                        @if(\App\Functions::is_admin()) <tr class="promotion-{{ $promotion->id }}"><td><input type="checkbox" class="select-one-checkbox"></td><td> @endif
                        <a href="/promotion/{{ $promotion->id }}"><b>{{ $promotion->title }}</b></a><br>{{ mb_substr(strip_tags($promotion->description), 0, 250) }}...
                        @if(\App\Functions::is_admin()) </td><td><button class="btn btn-primary btn-prom-edit">Редактировать</button></td></tr> @else <hr> @endif
                    @endforeach
                    @if(\App\Functions::is_admin())
                    <tr>
                        <td colspan="3">
                            @else
                                <hr>
                            @endif
                            {{ $promotions->paginate(\App\Settings::$posts_per_page)->links('vendor.pagination.default') }}
                            @if(\App\Functions::is_admin())
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            С выбранными:
                            <button class="btn btn-danger btn-proms-delete">Удалить</button>
                        </td>
                    </tr>
                    </tbody>
                </table> @endif
            @endif

            @if(\App\Functions::is_admin())
                <hr>
                <button class="btn btn-primary btn-prom-new" type="button" data-toggle="modal" data-target="#PromotionNewModal">Добавить новую акцию</button>
            @endif
        </div>
        <?php
        $file = json_decode('{ "pta_desc": "", "pta_add": "", "ptb_desc": "", "ptb_add": "", "ptc_desc": "", "ptc_add": "", "pta_banners_files": "[]", "ptb_banners_files": "[]", "ptc_banners_files": "[]" }');
        if(\Illuminate\Support\Facades\Storage::disk('local')->exists('promotion_special.json')){
            $file = json_decode(\Illuminate\Support\Facades\Storage::disk('local')->get('promotion_special.json'));
        }
        $file->pta_banners_files = json_decode($file->pta_banners_files);
        $file->ptb_banners_files = json_decode($file->ptb_banners_files);
        $file->ptc_banners_files = json_decode($file->ptc_banners_files);
        ?>
        <div id="prom_type_a">
            @if(\App\Functions::is_admin())
                @if($mode != 'edit')
                    <button class="btn btn-outline-primary" onclick="window.location.assign('/promotions?mode=edit&tab='+$('#tabs').tabs('option', 'active'));">Редактировать</button>
                @else
                    <button class="btn btn-outline-primary" onclick="window.location.assign('/promotions?mode=view&tab='+$('#tabs').tabs('option', 'active'));">Просмотр (изменения не сохранятся!)</button>
                @endif
            @endif
            <p><b>Описание:</b></p>
            @if (\App\Functions::is_admin() && $mode == 'edit')
                <textarea id="pta_desc">{!! $file->pta_desc !!}</textarea><br>
                <button class="btn btn-outline-primary btn-image-upload">Загрузить изображение</button>
                <input type="file" class="d-none file-prom-type-special" name="upload" accept="image/*"><br><br>
                <div class="pt_images">
                    @foreach($file->pta_banners_files as $key => $pta_banner_file)
                        @if(file_exists($_SERVER['DOCUMENT_ROOT'].'/images/promotions/'.$pta_banner_file))
                            <img src="/images/promotions/{{ $pta_banner_file }}?t={{ time() }}" class="pt_banner_file">
                        @else
                            @php
                                unset($file->pta_banners_files[$key]);
                            @endphp
                        @endif
                    @endforeach
                </div>
                <input type="hidden" name="pta_banners_files" value="{{ json_encode($file->pta_banners_files) }}">
                <br>
                <b>Дополнительная информация:</b>
                <textarea id="pta_add">{!! $file->pta_add !!}</textarea><br>
                <center><button class="btn btn-primary btn-special-save">Сохранить</button></center>
            @else
                <div class="border p-1 mb-1">
                    {!! $file->pta_desc !!}
                </div>
                    @foreach($file->pta_banners_files as $key => $pta_banner_file)
                        @if(file_exists($_SERVER['DOCUMENT_ROOT'].'/images/promotions/'.$pta_banner_file))
                            <img src="/images/promotions/{{ $pta_banner_file }}?t={{ time() }}">
                        @endif
                    @endforeach
                <p><b>Дополнительная информация:</b></p>
                <div class="border p-1 mb-1">
                    {!! $file->pta_add !!}
                </div>
            @endif
        </div>
        <div id="prom_type_b">
            @if(\App\Functions::is_admin())
                @if($mode != 'edit')
                    <button class="btn btn-outline-primary" onclick="window.location.assign('/promotions?mode=edit&tab='+$('#tabs').tabs('option', 'active'));">Редактировать</button>
                @else
                    <button class="btn btn-outline-primary" onclick="window.location.assign('/promotions?mode=view&tab='+$('#tabs').tabs('option', 'active'));">Просмотр (изменения не сохранятся!)</button>
                @endif
            @endif
            <p><b>Описание:</b></p>
            @if (\App\Functions::is_admin() && $mode == 'edit')
                <textarea id="ptb_desc">{!! $file->ptb_desc !!}</textarea><br>
                <button class="btn btn-outline-primary btn-image-upload">Загрузить изображение</button>
                <input type="file" class="d-none file-prom-type-special" name="upload" accept="image/*"><br><br>
                <div class="pt_images">
                    @foreach($file->ptb_banners_files as $key => $ptb_banner_file)
                        @if(file_exists($_SERVER['DOCUMENT_ROOT'].'/images/promotions/'.$ptb_banner_file))
                            <img src="/images/promotions/{{ $ptb_banner_file }}?t={{ time() }}" class="pt_banner_file">
                        @else
                            @php
                                unset($file->ptb_banners_files[$key]);
                            @endphp
                        @endif
                    @endforeach
                </div>
                <input type="hidden" name="ptb_banners_files" value="{{ json_encode($file->ptb_banners_files) }}">
                <br>
                <b>Дополнительная информация:</b>
                <textarea id="ptb_add">{!! $file->ptb_add !!}</textarea><br>
                <center><button class="btn btn-primary btn-special-save">Сохранить</button></center>
            @else
                <div class="border p-1 mb-1">
                    {!! $file->ptb_desc !!}
                </div>
                    @foreach($file->ptb_banners_files as $key => $ptb_banner_file)
                        @if(file_exists($_SERVER['DOCUMENT_ROOT'].'/images/promotions/'.$ptb_banner_file))
                            <img src="/images/promotions/{{ $ptb_banner_file }}?t={{ time() }}">
                        @endif
                    @endforeach
                <p><b>Дополнительная информация:</b></p>
                <div class="border p-1 mb-1">
                    {!! $file->ptb_add !!}
                </div>
            @endif
        </div>
        <div id="prom_type_c">
            @if(\App\Functions::is_admin())
                @if($mode != 'edit')
                    <button class="btn btn-outline-primary" onclick="window.location.assign('/promotions?mode=edit&tab='+$('#tabs').tabs('option', 'active'));">Редактировать</button>
                @else
                    <button class="btn btn-outline-primary" onclick="window.location.assign('/promotions?mode=view&tab='+$('#tabs').tabs('option', 'active'));">Просмотр (изменения не сохранятся!)</button>
                @endif
            @endif
            <p><b>Описание:</b></p>
            @if (\App\Functions::is_admin() && $mode == 'edit')
                <textarea id="ptc_desc">{!! $file->ptc_desc !!}</textarea><br>
                <button class="btn btn-outline-primary btn-image-upload">Загрузить изображение</button>
                <input type="file" class="d-none file-prom-type-special" name="upload" accept="image/*"><br><br>
                <div class="pt_images">
                    @foreach($file->ptc_banners_files as $key => $ptc_banner_file)
                        @if(file_exists($_SERVER['DOCUMENT_ROOT'].'/images/promotions/'.$ptc_banner_file))
                            <img src="/images/promotions/{{ $ptc_banner_file }}?t={{ time() }}" class="pt_banner_file">
                        @else
                            @php
                                unset($file->ptc_banners_files[$key]);
                            @endphp
                        @endif
                    @endforeach
                </div>
                <input type="hidden" name="ptc_banners_files" value="{{ json_encode($file->ptc_banners_files) }}">
                <br>
                <b>Дополнительная информация:</b>
                <textarea id="ptc_add">{!! $file->ptc_add !!}</textarea><br>
                <center><button class="btn btn-primary btn-special-save">Сохранить</button></center>
            @else
                <div class="border p-1 mb-1">
                    {!! $file->ptc_desc !!}
                </div>
                    @foreach($file->ptc_banners_files as $key => $ptc_banner_file)
                        @if(file_exists($_SERVER['DOCUMENT_ROOT'].'/images/promotions/'.$ptc_banner_file))
                            <img src="/images/promotions/{{ $ptc_banner_file }}?t={{ time() }}">
                        @endif
                    @endforeach
                <p><b>1Дополнительная информация:</b></p>
                <div class="border p-1 mb-1">
                    {!! $file->ptc_add !!}
                </div>
            @endif
        </div>
    </div>


    @if (\App\Functions::is_admin())
    <!-- Создание акции -->
    <div class="modal fade" id="PromotionNewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Добавить новую акцию</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        <b>Название:</b><br>
                        <input type="text" name="title" class="form-control input-prom-new">
                    </p>
                    <div>
                        <input type="file" class="d-none file-prom-new" accept="image/*">
                        <input type="hidden" class="input-prom-new" name="banner_file">
                        <button class="btn btn-outline-primary btn-banner-upload">Загрузить изображение</button>
                        <img class="d-none">
                    </div>
                    <p>
                        <b>Описание:</b><br>
                        <textarea name="description" id="description" class="input-prom-new"></textarea>
                    </p>
                    <p>
                        <b>Начинается:</b><br>
                        <input type="date" name="start" class="form-control input-prom-new">
                    </p>
                    <p>
                        <b>Заканчивается:</b><br>
                        <input type="date" name="end" class="form-control input-prom-new">
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary btn-save btn-prom-new">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Изменение акции -->
    <div class="modal fade" id="PromotionEditModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Редактировать акцию</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" class="input-prom-edit">
                    <p>
                        <b>Название:</b><br>
                        <input type="text" name="title" class="form-control input-prom-edit">
                    </p>
                    <div>
                        <input type="file" class="d-none file-prom-edit" accept="image/*">
                        <input type="hidden" class="input-prom-edit" name="banner_file">
                        <button class="btn btn-outline-primary btn-banner-upload">Загрузить изображение</button>
                        <img class="d-none">
                    </div>
                    <p>
                        <b>Описание:</b><br>
                        <textarea name="description" id="description-edit" class="input-prom-edit"></textarea>
                    </p>
                    <p>
                        <b>Начинается:</b><br>
                        <input type="date" name="start" class="form-control input-prom-edit">
                    </p>
                    <p>
                        <b>Заканчивается:</b><br>
                        <input type="date" name="end" class="form-control input-prom-edit">
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary btn-save btn-prom-edit">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Подтвердить удаление акций -->
    <div class="modal fade" id="PromsDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Удалить выбранные акции</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>Вы действительно хотите удалить эти акции?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-danger btn-save btn-proms-delete">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Подтвердить удаление изображения -->
    <div class="modal fade" id="BannerDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Удалить выбранное изображение</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>Вы действительно хотите удалить это изображение?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-danger btn-save btn-pt-banner-delete">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
    <script>

        let selected_promotions = [];

        $(document).ready(function(){
            $('#tabs').tabs();
            <?php
            $tab = 0;
            if (isset($_GET['tab'])){
                if ($_GET['tab'] >= 0 && $_GET['tab'] <= 3){
                    $tab = $_GET['tab'];
                }
            }
            ?>
            $('#tabs').tabs('option', 'active', <?php echo $tab; ?>);

            @if(\App\Functions::is_admin())
            let selectors = [
                '#pta_desc', '#pta_add',
                '#ptb_desc', '#ptb_add',
                '#ptc_desc', '#ptc_add',
                '#description', '#description-edit'
            ];
            for (let i = 0; i < selectors.length; i++) {
                tinymce.init({
                    selector: selectors[i],
                    <?php echo \App\Settings::$tinymce_settings; ?>
                });
            }
            reset_special_handlers();

            $('form').submit(function(e){
                e.preventDefault();
            });

            $('.btn-proms-delete').click(function(){
                if (selected_promotions.length > 0) {
                    if (!$(this).hasClass('btn-save')) {
                        $('#PromsDeleteModal').modal('show');
                    }
                    if ($(this).hasClass('btn-save')) {
                        let data = new FormData();
                        data.append('id_array', JSON.stringify(selected_promotions));
                        $.ajax({
                            url: '/api/promotion/delete_many',
                            method: 'post',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function(data, status, xhr){
                                if (status == 'success'){
                                    if (data.status == 'success'){
                                        window.location.reload();
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

                            }
                        });
                    }
                }
            });

            $('.btn-image-upload').click(function (e) {
                $(this).parent().children('.file-prom-type-special').click();
            });
            $('.file-prom-type-special').change(function (e) {
                let elem = this;
                if (this.files.length > 0){
                    let data = new FormData();
                    data.append('upload', this.files[0]);
                    $.ajax({
                        url: '/api/upload/'+$(this).parent().attr('id'),
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(data, status, xhr){
                            if (status == 'success'){
                                if (data.status == 'success'){
                                    $(elem).parent().children('.pt_images').append('<img src="/images/promotions/'+data.file_name+'?t='+(new Date).getTime()+'" class="pt_banner_file">');
                                    let files = JSON.parse($(elem).parent().children('[type=hidden]').val());
                                    files.push(data.file_name);
                                    $(elem).parent().children('[type=hidden]').val(JSON.stringify(files));
                                    reset_special_handlers();
                                }
                            }
                        },
                        error: function(xhr){
                            display_error(xhr);
                        }
                    });
                }
            });

            $('.btn-special-save').click(function(e){
                let data = new FormData();
                for (let i = 0; i < selectors.length; i++) {
                    tinymce.get(selectors[i].substr(1)).save();
                    data.append($(selectors[i]).attr('id'), $(selectors[i]).val());
                }
                data.append('pta_banners_files', $('[name=pta_banners_files]').val());
                data.append('ptb_banners_files', $('[name=ptb_banners_files]').val());
                data.append('ptc_banners_files', $('[name=ptc_banners_files]').val());
                $.ajax({
                    url: '/api/promotion/special/edit',
                    method: 'post',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data, status, xhr){
                        if (status == 'success'){
                            if (data.status == 'saved'){
                                window.location.assign('/promotions?mode=view&tab='+$('#tabs').tabs('option', 'active'));
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
                    }
                })
            });

            $('.btn-banner-upload').click(function(){
                $(this).parent().children('input[type=file]').click();
            });
            $('.file-prom-new, .file-prom-edit').change(function(){
                let par = $(this).parent();
                par.children('img').addClass('d-none');
                par.children('[name=banner_file]').val('null');
                if (this.files.length > 0){
                    let data = new FormData();
                    data.append('upload', this.files[0]);
                    $.ajax({
                        url: '/api/upload/banner',
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(data, status, xhr){
                            if (status == 'success'){
                                if (data.status == 'success'){
                                    par.children('[name=banner_file]').val(data.file_name);
                                    par.children('img').attr('src', '/images/promotions/'+data.file_name+'?t='+(new Date).getTime());
                                    par.children('img').removeClass('d-none');
                                }
                            }
                        },
                        error: function (xhr) {
                            display_error(xhr);

                        }
                    });
                }
            });

            $('.btn-prom-new').click(function(){
                if ($(this).hasClass('btn-save')){
                    tinymce.get('description').save();
                    if ($('.input-prom-new[name=banner_file]').val().trim() != '' &&
                        $('.input-prom-new[name=description]').val().trim() != '') {
                        let data = new FormData();
                        $('.input-prom-new').each(function (i, elem) {
                            data.append($(elem).attr('name'), $(elem).val());
                        });
                        $.ajax({
                            url: '/api/promotion/add',
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
                    }
                }
            });

            $('.btn-prom-edit').click(function(){
                if (!$(this).hasClass('btn-save')){
                    let id;
                    $(this).parent().parent()[0].className.split(' ').forEach(function(elem, i, arr){
                        if (elem.split('-')[0] == 'promotion' && elem.split('-').length == 2){
                            id = elem.split('-')[1];
                        }
                    });
                    $.ajax({
                        url: '/api/promotion/'+id+'/get',
                        method: 'get',
                        data: null,
                        processData: false,
                        contentType: false,
                        success: function (data, status, xhr) {
                            if (status == 'success') {
                                if (data.status == 'found') {
                                    $('.input-prom-edit[name=id]').val(data.object.id);
                                    $('.input-prom-edit[name=title]').val(data.object.title);
                                    $('.input-prom-edit[name=banner_file]').val(data.object.banner_file);
                                    $('.input-prom-edit[name=banner_file]').parent().children('img').removeClass('d-none');
                                    $('.input-prom-edit[name=banner_file]').parent().children('img').attr('src', '/images/promotions/'+data.object.banner_file+'?t='+(new Date).getTime());
                                    $('.input-prom-edit[name=description]').val(data.object.description);
                                    tinymce.get('description-edit').load();
                                    $('.input-prom-edit[name=start]').val(data.object.start);
                                    $('.input-prom-edit[name=end]').val(data.object.end);
                                    $('#PromotionEditModal').modal('show');
                                }
                            }
                        },
                        error: function (xhr) {
                            display_error(xhr);

                        }
                    });
                }
                if ($(this).hasClass('btn-save')){
                    tinymce.get('description-edit').save();
                    if ($('.input-prom-edit[name=banner_file]').val().trim() != '' &&
                        $('.input-prom-edit[name=description]').val().trim() != '') {
                        let data = new FormData();
                        let id;
                        $('.input-prom-edit').each(function (i, elem) {
                            if ($(elem).attr('name') == 'id')
                                id = $(elem).val();
                            else
                                data.append($(elem).attr('name'), $(elem).val());
                        });
                        $.ajax({
                            url: '/api/promotion/'+id+'/edit',
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
                    }
                }
            });

            $('.select-all-checkbox').change(function (e) {
                $(this).parent().parent().parent().children('tr').children('td').children('.select-one-checkbox').prop('checked', $(this).prop('checked'));
                if ($(this).prop('checked')){
                    $('tr').each(function(index, elem){
                        if (elem.className.indexOf('promotion-') != -1){
                            let classes = elem.className.split(' ');
                            classes.forEach(function(value, index, array){
                                if (value.split('-').length == 2){
                                    if (value.split('-')[0] == 'promotion'){
                                        if (selected_promotions.indexOf(value.split('-')[1]) == -1)
                                            selected_promotions.push(value.split('-')[1]);
                                    }
                                }
                            });
                        }
                    });
                } else {
                    selected_promotions = [];
                }
            });

            $('.select-one-checkbox').change(function (e) {
                let select = this;
                let classes = $(this).parent().parent()[0].className.split(' ');
                classes.forEach(function(value, index, array){
                    if (value.split('-').length == 2){
                        if (value.split('-')[0] == 'promotion'){
                            if ($(select).prop('checked') && selected_promotions.indexOf(value.split('-')[1]) == -1){
                                selected_promotions.push(value.split('-')[1]);
                            } else {
                                selected_promotions.forEach(function(val, index, array){
                                    if (val == value.split('-')[1]){
                                        array.splice(index, 1);
                                    }
                                });
                            }
                        }
                    }
                });
            });

            let pt_img_parent, pt_img_file_name;
            $('.btn-pt-banner-delete').click(function(){
                $.ajax({
                    url: '/api/images/promotions/'+pt_img_file_name+'/delete',
                    method: 'get',
                    data: null,
                    processData: false,
                    contentType: false,
                    success: function(data, status, xhr){
                        if (status == 'success'){
                            if (data.status == 'deleted'){
                                let files = JSON.parse($(pt_img_parent).parent().parent().children('[type=hidden]').val());
                                files.forEach(function(elem, i, arr){
                                    if (elem == pt_img_file_name){
                                        arr.splice(i, 1);
                                    }
                                });
                                $(pt_img_parent).parent().parent().children('[type=hidden]').val(JSON.stringify(files));
                                $(pt_img_parent).remove();
                                $('#BannerDeleteModal').modal('hide');
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
                    }
                });
            });

            function reset_special_handlers(){
                $('.pt_banner_file').unbind('click');
                $('.pt_banner_file').click(function(){
                    pt_img_parent = this;
                    pt_img_file_name = this.src.split('/')[this.src.split('/').length - 1].split('?')[0];
                    $('#BannerDeleteModal').modal('show');
                });
            }

            @endif
        });
    </script>
@endsection
