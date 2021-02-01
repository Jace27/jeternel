@extends('layout')

<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../functions.php';
$cat = \App\Models\service_categories::where('id', $id);
$cat_exist = false;
if ($cat->first() == null){
    $cat = new \App\Models\service_categories();
    $cat->name = 'Категории не существует';
} else {
    $cat = $cat->first();
    $cat_exist = true;
}
?>

@section('title')
{{ $cat->name }} - База знаний Jeternel @endsection

@section('body')
    <?php
    if (!$cat_exist){
        echo '<h3>Категории не существует</h3>';
    } else {
        ?>
        <h2>
            Категория: <div class="d-inline-block value value-cat-name">{{ $cat->name }}</div>
            @if(isAdmin())
                <button class="btn btn-cat-edit" title="Изменить название">
                    <img src="/images/icons/edit.svg">
                </button>
                <button class="btn btn-cat-delete" title="Удалить" type="button" data-toggle="modal" data-target="#CatDeleteModal">
                    <img src="/images/icons/trash.svg">
                </button>
            @endif
        </h2>
        <?php
        if (count($cat->children()->get()) != 0){ ?>
            <h3>Подкатегории:</h3>
            @if(isAdmin())
                <p>
                    <button class="btn btn-link btn-cat-add" type="button" data-toggle="modal" data-target="#CatAddModal">Добавить существующую</button>
                    <button class="btn btn-link btn-cat-new" type="button" data-toggle="modal" data-target="#CatNewModal">Создать новую</button>
                </p>
            @endif
            <?php
            foreach ($cat->children()->get() as $subcat){
                ?>
                <p>
                    <a href="/category/{{ $subcat->id }}">
                        <b class="cat-{{ $subcat->id }}">{{ $subcat->name }}</b>
                    </a>
                    <button class="btn btn-cat-delete-child" title="Удалить" type="button" data-toggle="modal" data-target="#CatDeleteChildModal">
                        <img src="/images/icons/trash.svg">
                    </button>
                </p>
                <hr>
                <?php
            }
        } else { ?>
            <h3>Нет подкатегорий</h3>
            @if(isAdmin())
                <p>
                    <button class="btn btn-link btn-cat-add" type="button" data-toggle="modal" data-target="#CatAddModal">Добавить существующую</button>
                    <button class="btn btn-link btn-cat-new" type="button" data-toggle="modal" data-target="#CatNewModal">Создать новую</button>
                </p>
            @endif
            <?php
        }

        if (count($cat->services()->get()) != 0){
            echo '<h3>Услуги:</h3>';
            foreach ($cat->services()->get() as $service){
                echo '<p><a href="/service/'.$service->id.'"><b>'.$service->name.'</b></a>'.( $service->trashed() ? ' - <span style="color: rgba(225, 25, 25, 0.75)">услуга больше не оказывается</span>' : '' ).'<br>'.mb_substr(strip_tags($service->description), 0, 250).'...'.'</p><hr>';
            }
        } else {
            echo '<h3>Нет услуг</h3>';
        }
    }
    ?>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
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
                                    }
                                }
                            });
                        }
                    });
                }
            });

            $('.btn-cat-delete').click(function () {
                $('#CatDeleteModal').modal('toggle');
                if ($(this).hasClass('btn-save')){
                    $.ajax({
                        url: '/api/category/{{ $cat->id }}/delete',
                        method: 'get',
                        processData: false,
                        contentType: false,
                        success: function (data, status, xhr) {
                            console.log([data, status, xhr]);
                            if (data.status == 'success'){
                                window.location.assign('/');
                            }
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
                            }
                        }
                    });
                }
            });

            $('.btn-cat-delete-child').click(function () {
                if ($(this).hasClass('btn-save')){
                    let data = new FormData();
                    data.append('field', 'parent_category_id');
                    data.append('value', 'null');
                    $.ajax({
                        url: '/api/category/'+$('.btn-cat-delete-child').prev().children('b').attr('class').split('-')[1]+'/edit_field',
                        method: 'post',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(data, status, xhr){
                            console.log([data, status, xhr]);
                            if (data.status == 'success') {
                                window.location.reload();
                            }
                        }
                    });
                } else {
                    $('.cat-delete-child-name').html($('.btn-cat-delete-child').prev().children('b').text());
                }
            });
        });
    </script>
@endsection
