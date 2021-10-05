@extends('layout')

@section('title')
    {{ \App\Settings::$title_site_name }} @endsection

@section('body')
    <h3>Панель управления</h3>
    <hr>
    <p><a href="/users/">Пользователи</a></p>
    <hr>
    <p><a href="/log/">Посещения</a></p>
    <hr>
    <p><a href="/performers/">Специалисты</a></p>
    <hr>
    <!--<p><a href="#">Филиалы</a></p>
    <hr>-->
@endsection
