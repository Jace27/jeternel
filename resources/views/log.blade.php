@extends('layout')

@section('title')
Журнал посещений - {{ \App\Settings::$title_site_name }} @endsection

@section('body')
    <p>
        Сортировка по:
        <select id="sortby-select" class="form-control d-inline-block w-auto">
            <option value="desc">убыванию</option>
            <option value="asc"<?php if (isset($_GET['sort']) && $_GET['sort'] == 'asc') echo ' selected'; ?>>возрастанию</option>
        </select>
    </p>
    <table class="table">
        <tbody>
        <tr>
            <th>Фамилия</th>
            <th>Имя</th>
            <th>Время</th>
        </tr>
        <?php
        $logs = \App\Models\signin_logs::orderBy('time', isset($_GET['sort']) ? $_GET['sort'] : 'desc')->paginate(\App\Settings::$posts_per_page);
        ?>
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->user()->first()->last_name }}</td>
                <td>{{ $log->user()->first()->first_name }}</td>
                <td>{{ date('d.m.Y H:i:s', strtotime($log->time)) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $logs->appends(['sort'=>(isset($_GET['sort']) ? $_GET['sort'] : 'desc')])->links('vendor.pagination.default') }}
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#sortby-select').change(function(e){
                let select = $(this).val();
                let search = [];
                if (window.location.search != "") {
                    search = window.location.search.split('?')[1].split('&');
                    if (window.location.search.indexOf('sort=') != -1) {
                        search.forEach(function (value, index, array) {
                            if (value.split('=')[0] == 'sort') {
                                let v = value.split('=');
                                v[1] = select;
                                array[index] = v.join('=');
                            }
                        });
                    } else {
                        search.push('sort=' + select);
                    }
                } else {
                    search.push('sort=' + select);
                }
                window.location.assign('?'+search.join('&'));
            });
        });
    </script>
@endsection
