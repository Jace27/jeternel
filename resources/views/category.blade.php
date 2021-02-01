@extends('layout')

<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../functions.php';
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
{{ $cat->name }} - База знаний Jeternel @endsection

@section('body')
    <?php
    if (!$cat_exist){
        echo '<h3>Категории не существует</h3>';
    } else {
        ?>
        <h2>
            Категория: <div class="d-inline-block value value-cat-name">{{ $cat->name }}</div>
            @if(isAdmin() && $id != 0)
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
        } else if ($id != 0) { ?>
            <h3>Нет подкатегорий</h3>
            @if(isAdmin())
                <p>
                    <button class="btn btn-link btn-cat-add" type="button" data-toggle="modal" data-target="#CatAddModal">Добавить существующую</button>
                    <button class="btn btn-link btn-cat-new" type="button" data-toggle="modal" data-target="#CatNewModal">Создать новую</button>
                </p>
            @endif
            <?php
        }

        if (count($cat->services()->get()) != 0 || $id == 0){
            echo '<h3>Услуги:</h3>';
            $list = $cat->services()->get();
            if ($id == 0)
                $list = \App\Models\services::all();
            foreach ($list as $service){
                if (($id == 0 && $service->categories()->first() == null) || $id != 0)
                    echo '<p><a href="/service/'.$service->id.'"><b>'.$service->name.'</b></a>'.( $service->trashed() ? ' - <span style="color: rgba(225, 25, 25, 0.75)">услуга больше не оказывается</span>' : '' ).'<br>'.mb_substr(strip_tags($service->description), 0, 250).'...'.'</p><hr>';
            }
        } else {
            echo '<h3>Нет услуг</h3>';
        }
    }
    ?>



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
                        $possible_child_cats = \App\Models\service_categories::where('type_id', $cat->type_id)->get();
                        foreach ($possible_child_cats as $pcat){
                            // категория не может быть сама себе родителем и не может иметь своего родителя в качестве своего же ребенка
                            if ($cat->id != $pcat->id &&
                                ($cat->parent()->first() == null ||
                                    ($cat->parent()->first() != null && $cat->parent()->first()->id != $pcat->id))){
                                echo '<option value="'.$pcat->id.'">'.$pcat->name.'</option>';
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
                    <h5 class="modal-title">Удалить категорию</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>Вы действительно хотите удалить категорию {{ $cat->name }}?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-danger btn-save btn-cat-delete">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Подтвердить удаление категории из числа подкатегорий -->
    <div class="modal fade" id="CatDeleteChildModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Удалить категорию из числа подкатегорий</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Вы действительно хотите удалить <span class="cat-delete-child-name"></span> из числа дочерних категорий?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-danger btn-save btn-cat-delete-child">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>
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
