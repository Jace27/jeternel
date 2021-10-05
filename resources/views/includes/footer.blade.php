<script type="text/javascript" src="/js/jquery-3.5.1.js"></script>
<script type="text/javascript" src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/bootstrap/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="/js/header.js"></script>
<script type="text/javascript" src="/js/menu.js"></script>
@if (\App\Functions::is_admin())
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
                $type_cats = $type->categories()->orderBy('name')->get();
                foreach ($type_cats as $tcat){
                    echo "<option value=\\\"".$tcat->id."\\\">".$tcat->name."</option>";
                }
                echo '", ';
            }
            ?>
        ];
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
                            window.location.reload();
                            //window.location.assign('/category/' + data.id);
                        } else {
                            display_error(xhr);
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
</script>
<script>
    <?php
    $page = 'service';
    if (strpos($_SERVER['REQUEST_URI'], '/article') === 0)
        $page = 'article';
    //if (strpos($_SERVER['REQUEST_URI'], '/new') === 0)
    //    $page = 'new';
    if (strpos($_SERVER['REQUEST_URI'], '/promotion') === 0)
        $page = 'promotion';
    ?>
    $('input.search').click(function(e){
        e.stopPropagation();
    });
    $('.search-input').click(function(e){
        e.stopPropagation();
        if (this.nodeName != 'INPUT')
            search('main', '{{ $page }}');
    });
    $('input.search').on('input', function(e){
        search('offer', '{{ $page }}');
    });
    $('input.search').on('focus', function () {
        search('offer', '{{ $page }}');
    });
    $('input.search').on('keypress', function(e){
        if (e.originalEvent.key == 'Enter'){
            if (selected_offer == -1) {
                search('main', '{{ $page }}');
            } else {
                $('input.search').val($($('.search-offer')[selected_offer]).text());
                selected_offer = -1;
                select_offer();
                setTimeout(function () {
                    $('.search-offers').html('');
                    $('.search-offers').addClass('d-none');
                }, 50);
                search('main', '{{ $page }}');
            }
        }
    });
    @if(!\App\Functions::is_admin())
        $(document).on('keypress', function (e) {
            $('input.search').focus();
        });
    @endif
</script>
@yield('scripts')
