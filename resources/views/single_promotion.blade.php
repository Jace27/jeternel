@extends('layout')

<?php
$prom = \App\Models\promotions::where('id', $id);
$prom_exist = false;
if ($prom->first() == null){
    $prom = new \App\Models\promotions();
    $prom->name = 'Акции не существует';
} else {
    $prom = $prom->first();
    $prom_exist = true;
}
?>

@section('title')
Акция {{ $prom->title }} - {{ \App\Settings::$title_site_name }} @endsection

@section('styles')
    <style>

    </style>
@endsection

@section('body')
    @if(!$prom_exist)
        <h3>Акции не существует</h3>
    @else
        <h2>Акция:
            @if(\App\Functions::is_admin()) <input name="title" class="input-prom-edit" value="@endif{{ $prom->title }}@if(\App\Functions::is_admin())"> @endif
            &nbsp;
            @if(\App\Functions::is_admin())
                <button class="btn btn-danger btn-prom-delete" data-toggle="modal" data-target="#PromDeleteModal">Удалить</button>
            @endif
        </h2>
        <div class="w-75 align-center" style="margin: 0 12.5%">
            @if(\App\Functions::is_admin())
                <input type="file" class="d-none file-banner-upload" name="upload" accept="image/*">
                <input type="hidden" name="banner_file" class="input-prom-edit" value="{{ $prom->banner_file }}">
                <button class="btn btn-primary btn-banner-upload">Загрузить изображение</button>
            @endif
            <img @if(file_exists($_SERVER['DOCUMENT_ROOT'].'/images/promotions/'.$prom->banner_file)) src="/images/promotions/{{ $prom->banner_file }}" class="img-banner" @else class="img-banner d-none" @endif >
        </div>
        <div class="mt-2">@if(\App\Functions::is_admin()) <textarea name="description" id="description" class="input-prom-edit"> @endif {!! $prom->description !!} @if(\App\Functions::is_admin()) </textarea> @endif
        </div>
        <div class="mt-2">
            <b>Начало:</b>
            @if(\App\Functions::is_admin()) <input type="date" class="form-control input-prom-edit" name="start" value="@endif{{ $prom->start }}@if(\App\Functions::is_admin())"> @endif
        </div>
        <div class="mt-2">
            <b>Окончание:</b>
            @if(\App\Functions::is_admin()) <input type="date" class="form-control input-prom-edit" name="end" value="@endif{{ $prom->end }}@if(\App\Functions::is_admin())"> @endif
        </div>
        @if(\App\Functions::is_admin())
            <button class="btn btn-primary btn-prom-save mt-2">Сохранить изменения</button>
        @endif
    @endif

    @if (\App\Functions::is_admin())
        <!-- Подтвердить удаление акции -->
        <div class="modal fade" id="PromDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Удалить данную акцию</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h3>Вы действительно хотите удалить эту акцию?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                        <button type="button" class="btn btn-danger btn-save btn-prom-delete">Подтвердить</button>
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
                    selector: '#description',
                    <?php echo \App\Settings::$tinymce_settings; ?>
                });

                $('.btn-banner-upload').click(function(){
                    $('.file-banner-upload').click();
                });
                $('.file-banner-upload').change(function(){
                    if (this.files.length > 0) {
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
                                        $('.img-banner').removeClass('d-none');
                                        $('.input-prom-edit[name=banner_file]').val(data.file_name);
                                        $('.img-banner').attr('src', '/images/promotions/'+data.file_name+'?t='+(new Date).getTime());
                                        return;
                                    }
                                }
                                $('.img-banner').addClass('d-none');
                            },
                            error: function (xhr) {
                                $('.img-banner').addClass('d-none');
                                $('.input-prom-edit[name=banner_file]').val('null');

                                display_error(xhr);

                            }
                        });
                    }
                });

                $('.btn-prom-save').click(function(){
                    if ($('.input-prom-edit[name=banner_file]').val().trim() != '' &&
                        $('.input-prom-edit[name=description]').val().trim() != ''){
                        tinymce.get('description').save();
                        let data = new FormData();
                        $('.input-prom-edit').each(function(i, elem){
                            let val = $(elem).val();
                            if ($(elem).attr('type') == 'datetime-local')
                                val = val.replace('T', ' ');
                            data.append($(elem).attr('name'), val);
                        });
                        $.ajax({
                            url: '/api/promotion/{{ $prom->id }}/edit',
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
                            error: function (xhr) {
                                display_error(xhr);

                            }
                        });
                    }
                });

                $('.btn-prom-delete').click(function(){
                    if ($(this).hasClass('btn-save')){
                        $.ajax({
                            url: '/api/promotion/{{ $prom->id }}/delete',
                            method: 'get',
                            data: null,
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
                                display_error(xhr);

                            }
                        });
                    }
                });
            });
        </script>
    @endif
@endsection
