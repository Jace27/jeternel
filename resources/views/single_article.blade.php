@extends('layout')

@php
$article_exist = true;
$article = \App\Models\articles::where('id', $id)->first();
if ($article == null){
    $article = new \App\Models\articles();
    $article->name = "Статьи не существует";
    $article->section_id = -1;
    $article_exist = false;
}
@endphp

@section('title')
{{ $article->name }} - {{ \App\Settings::$title_site_name }} @endsection

@section('body')
    <h3>{{ $article->name }}</h3>
    @if(\App\Functions::is_admin()) <button class="btn btn-primary" onclick="window.location.assign('/article/{{ $article->id }}/edit');">Редактировать</button>
    <br><br> @endif
    @if($article_exist)
    <div>{!! $article->content !!}</div>
    @endif
@endsection
