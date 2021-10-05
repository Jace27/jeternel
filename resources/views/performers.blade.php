@extends('layout')

@section('title')
Специалисты - {{ \App\Settings::$title_site_name }} @endsection

@section('body')
    @if(count(\App\Models\performers::all()) == 0)
        <h3>Нет специалистов</h3>
    @else
        @if(\App\Functions::is_admin())

        <div class="alert alert-success" role="alert" style="display: none;"></div>
        <table class="table performers">
            <tbody>
            <tr>
                <th width="30"><input type="checkbox" class="select-all-checkbox"></th>
                <th>Специалист</th>
                <th>Статус</th>
                <th></th>
            </tr> @endif
            @foreach(\App\Models\performers::orderBy('last_name', 'asc')->paginate(\App\Settings::$posts_per_page) as $performer)
                @if(\App\Functions::is_admin()) <tr class="performer-{{ $performer->id }}"><td><input type="checkbox" class="select-one-checkbox"></td><td> @endif
                    {{ $performer->last_name }} {{ $performer->first_name }} {{ $performer->second_name }}, {{ $performer->type()->first()->name }}
                @if(\App\Functions::is_admin()) </td>
                    <td>
                        <select class="form-control select-performer-status">
                            @foreach(\App\Models\performers_statuses::all() as $status)
                                @php
                                    $selected = false;
                                    foreach($performer->statuses()->get() as $stat){
                                        if ($stat->status_id == $status->id){
                                            if(($stat->start == null || strtotime($stat->start) <= time()) &&
                                                ($stat->end == null || strtotime($stat->end) >= time())){
                                                $selected = true;
                                            }
                                        }
                                    }
                                @endphp
                                <option value="{{ $status->id }}" @if($selected) selected @endif>{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-performer-edit">Редактировать</button>
                    </td>
                </tr> @else <hr> @endif
            @endforeach
            <tr>
                <td colspan="4">
                    {{ \App\Models\performers::paginate(\App\Settings::$posts_per_page)->links('vendor.pagination.default') }}
                </td>
            </tr>
            @if(\App\Functions::is_admin())
            <tr>
                <td colspan="4">
                    С выбранными:
                    <button class="btn btn-danger btn-performers-delete">Удалить</button>
                </td>
            </tr>
            </tbody>
        </table> @endif
    @endif

    @if(\App\Functions::is_admin())
        <hr>
        <button class="btn btn-primary btn-performer-add">Добавить нового специалиста</button>
    @endif


    @if (\App\Functions::is_admin())
        <!-- Подтвердить удаление специалиста -->
        <div class="modal fade" id="PerfsDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Удалить выбранных специалистов</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h3>Вы действительно хотите удалить этих специалистов?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                        <button type="button" class="btn btn-danger btn-save btn-performers-delete">Подтвердить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Указать даты начала и конца статуса специалиста -->
        <div class="modal fade" id="StartEndStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Укажите даты начала и конца статуса специалиста</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="status_id" class="input-performer-status">
                        <input type="hidden" name="performer_id" class="input-performer-status">
                        <p>
                            Начало:
                            <input type="date" name="start" class="input-performer-status">
                        </p>
                        <p>
                            Конец:
                            <input type="date" name="end" class="input-performer-status">
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                        <button type="button" class="btn btn-primary btn-save btn-performer-set-status">Подтвердить</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>

        let selected_performers = [];

        $(document).ready(function(){
            console.log('init');

            @if(\App\Functions::is_admin())

            $('.select-performer-status').change(function() {
                let id;
                $(this).parent().parent()[0].className.split(' ').forEach(function (elem, i, arr) {
                    if (elem.split('-').length == 2 && elem.split('-')[0] == 'performer') {
                        id = elem.split('-')[1];
                    }
                });
                $('.input-performer-status[name=status_id]').val($(this).val());
                $('.input-performer-status[name=performer_id]').val(id);
                $('#StartEndStatusModal').modal('show');
            });
            $('.btn-performer-set-status').click(function() {
                let data = new FormData();
                let id;
                $('.input-performer-status').each(function (i, elem) {
                    console.log($(elem).val());
                    if ($(elem).attr('name') == 'performer_id')
                        id = $(elem).val();
                    let val = $(elem).val();
                    if (val.trim() == '') val = 'null';
                    data.append($(elem).attr('name'), val);
                });
                $.ajax({
                    url: '/api/performer/' + id + '/status/set',
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
            });

            $('.btn-performer-add').click(function(e){
                window.location.assign('/performer/new');
            });

            $('.btn-performer-edit').click(function(e){
                let id;
                $(this).parent().parent()[0].className.split(' ').forEach(function(elem, i, arr){
                    if (elem.split('-').length == 2 && elem.split('-')[0] == 'performer'){
                        id = elem.split('-')[1];
                    }
                });
                window.location.assign('/performer/'+id+'/edit');
            });

            $('.btn-performers-delete').click(function(){
                if (selected_performers.length > 0) {
                    if (!$(this).hasClass('btn-save')) {
                        $('#PerfsDeleteModal').modal('show');
                    }
                    if ($(this).hasClass('btn-save')) {
                        let data = new FormData();
                        data.append('id_array', JSON.stringify(selected_performers));
                        $.ajax({
                            url: '/api/performer/delete_many',
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

            $('.select-all-checkbox').change(function (e) {
                $(this).parent().parent().parent().children('tr').children('td').children('.select-one-checkbox').prop('checked', $(this).prop('checked'));
                if ($(this).prop('checked')){
                    $('tr').each(function(index, elem){
                        if (elem.className.indexOf('performer-') != -1){
                            let classes = elem.className.split(' ');
                            classes.forEach(function(value, index, array){
                                if (value.split('-').length == 2){
                                    if (value.split('-')[0] == 'performer'){
                                        if (selected_performers.indexOf(value.split('-')[1]) == -1)
                                            selected_performers.push(value.split('-')[1]);
                                    }
                                }
                            });
                        }
                    });
                } else {
                    selected_performers = [];
                }
            });

            $('.select-one-checkbox').change(function (e) {
                let select = this;
                let classes = $(this).parent().parent()[0].className.split(' ');
                classes.forEach(function(value, index, array){
                    if (value.split('-').length == 2){
                        if (value.split('-')[0] == 'performer'){
                            if ($(select).prop('checked') && selected_performers.indexOf(value.split('-')[1]) == -1){
                                selected_performers.push(value.split('-')[1]);
                            } else {
                                selected_performers.forEach(function(val, index, array){
                                    if (val == value.split('-')[1]){
                                        array.splice(index, 1);
                                    }
                                });
                            }
                        }
                    }
                });
            });

            @endif
        });
    </script>
@endsection
