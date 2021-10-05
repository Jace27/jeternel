@extends('layout')

@section('title')
Календарь диспорта - {{ \App\Settings::$title_site_name }} @endsection

@section('body')
    <h3 class="align-center">Календарь диспорта</h3>
    <div class="d-grid" style="grid-template-columns: 1fr 3fr 1fr 3fr 1fr">
        <div>&nbsp;</div>
        <div>
            <h5 class="align-center">Текущий месяц ({{ \App\Functions::translate_month(date('F')) }})</h5>
            <?php
            $files = glob($_SERVER['DOCUMENT_ROOT'].'/images/disport/'.date('Y-m').'*.png');
            if (count($files) == 0)
                echo '<div class="border align-center"><img src="/images/disport/default.png"></div>';
            else {
                $file = $files[0];
                foreach ($files as $f){
                    if (strtotime(mb_substr($f, mb_strpos($f, date('Y-m')), 17)) > strtotime(mb_substr($file, mb_strpos($file, date('Y-m')), 17))){
                        $file = $f;
                    }
                }
                echo '<div class="border align-center"><img src="'.mb_substr($file, mb_strpos($file, $_SERVER['DOCUMENT_ROOT']) + mb_strlen($_SERVER['DOCUMENT_ROOT'])).'"></div>';
            }
            ?>
        </div>
        <div>&nbsp;</div>
        <div>
            <h5 class="align-center">Следующий месяц ({{ \App\Functions::translate_month(date('F', time()+60*60*24*30)) }})</h5>
            <?php
            $files = glob($_SERVER['DOCUMENT_ROOT'].'/images/disport/'.date('Y-m', time()+60*60*24*30).'*.png');
            if (count($files) == 0)
                echo '<div class="border align-center"><img src="/images/disport/default.png"></div>';
            else {
                $file = $files[0];
                foreach ($files as $f){
                    if (strtotime(mb_substr($f, mb_strpos($f, date('Y-m')), 17)) > strtotime(mb_substr($file, mb_strpos($file, date('Y-m')), 17))){
                        $file = $f;
                    }
                }
                echo '<div class="border align-center"><img src="'.mb_substr($file, mb_strpos($file, $_SERVER['DOCUMENT_ROOT']) + mb_strlen($_SERVER['DOCUMENT_ROOT'])).'"></div>';
            }
            ?>
        </div>
        <div>&nbsp;</div>
    </div>
    @if (\App\Functions::is_admin())
    <hr>
    <h4>Загрузить календарь</h4>
    <form action="/api/upload/disport" enctype="multipart/form-data" method="post" id="disport_upload_form">
        <p><select name="month" class="form-control">
            <option value="current">Текущий месяц</option>
            <option value="next">Следующий месяц</option>
        </select></p>
        <p>
            <button id="activate_upload_input" class="btn btn-outline-primary">Загрузить файл...</button>
            <input type="file" name="upload" class="d-none">
        </p>
        <p><input type="submit" value="Загрузить" class="btn btn-primary"></p>
    </form>
    @endif
@endsection

@section('scripts')
    @if(\App\Functions::is_admin())
    <script>
        $(document).ready(function(){
            $('#activate_upload_input').click(function(e){
                $('input[name=upload]').click();
            });
            $('input[name=upload]').change(function(e){
                console.log($('input[name=upload]')[0].files);
                if ($('input[name=upload]')[0].files[0] != null){
                    $('#activate_upload_input').text('Файл "'+$('input[name=upload]')[0].files[0].name+'"');
                }
            });

            $('#disport_upload_form').submit(function(e){
                e.preventDefault();

                let data = new FormData();
                data.append('month', $('select[name=month]').val());
                data.append('upload', $('input[name=upload]')[0].files[0]);
                $.ajax({
                    url: '/api/upload/disport',
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
                                $('#p-modal-error').html(xhr.responseJSON.message);
                            else
                                $('#p-modal-error').html(xhr.responseText);
                        } else {
                            $('#p-modal-error').html(xhr.responseText);
                        }

                    }
                });
            });
        });
    </script>
    @endif
@endsection
