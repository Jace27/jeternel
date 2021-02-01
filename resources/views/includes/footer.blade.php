<script type="text/javascript" src="/js/jquery-3.5.1.js"></script>
<script type="text/javascript" src="/js/bootstrap/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="/js/header.js"></script>
<script type="text/javascript" src="/js/menu.js"></script>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/../functions.php'; ?>
@if (isAdmin())
    @include('includes.modals')
@endif
<script>
    $('#cat-new-type-select').change(function (e) {
        change_cat_set();
    });
    function change_cat_set(){
        let cat_sets = [
            <?php
            $cat_types = \App\Models\service_categories_types::all();
            foreach ($cat_types as $type){
                echo '"<option value=\"null\">Отсутствует</option>';
                $type_cats = $type->categories()->get();
                foreach ($type_cats as $tcat){
                    echo "<option value=\\\"".$tcat->id."\\\">".$tcat->name."</option>";
                }
                echo '", ';
            }
            ?>
        ];
        //console.log($('#cat-new-type-select')[0].options.selectedIndex);
        $('#cat-new-parent-select').html(cat_sets[$('#cat-new-type-select')[0].options.selectedIndex]);
    }

    $('.btn-cat-new').click(function () {
        if ($(this).hasClass('btn-save')){
            if ($('#cat-new-name').val().trim() != ''){
                let data = new FormData();
                data.append($('#cat-new-name').attr('name'), $('#cat-new-name').val());
                data.append($('#cat-new-parent-select').attr('name'), $('#cat-new-parent-select').val());
                data.append($('#cat-new-type-select').attr('name'), $('#cat-new-type-select').val());
                $.ajax({
                    url: '/api/category/add',
                    method: 'post',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (data, status, xhr) {
                        console.log([data, status, xhr]);
                        if (data.status == 'success') {
                            window.location.assign('/category/' + data.id);
                        }
                    }
                });
            }
        }
    });
</script>
@yield('scripts')
