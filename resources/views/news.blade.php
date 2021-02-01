@extends('layout')

@section('title')
Новости - База знаний Jeternel @endsection

@section('body')
    <?php
    $news = \App\Models\news::all()->sortByDesc('is_important');
    if (count($news) == 0){
        echo '<h3>Нет новостей</h3>';
    } else {
        echo '<h3>Новости</h3>';
        foreach ($news as $n){
            echo '<div'.( $n->is_important == 1 ? ' class="important_news"' : '' ).'><p><b>'.$n->title.'</b></p>';
            echo '<p>'.$n->content.'</p></div><hr>';
        }
    }
    ?>
@endsection
