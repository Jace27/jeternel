@extends('layout')

@section('title')
База знаний Jeternel @endsection

@section('body')
    <?php
    $news = \App\Models\news::where('is_important', 1)->get();
    if (count($news) == 0){
        echo '<h3>Нет новостей</h3>';
    } else {
        echo '<h3>Новости:</h3>';
        foreach ($news as $n){
            echo '<div class="important_news"><p><b>'.$n->title.'</b></p>';
            echo '<p>'.$n->content.'</p></div><hr>';
        }
    }
    ?>
    <?php
    $promotions = \App\Models\promotions::all();
    if (count($promotions) == 0){
        echo 'Нет акций';
    } else {
        echo '<h3>Текущие акции:</h3>';
        foreach ($promotions as $promotion){
            echo '<p><a href="/promotion/'.$promotion->id.'"><b>'.$promotion->title.'</b></a><br>'.mb_substr(strip_tags($promotion->description), 0, 250).'...'.'</p><hr>';
        }
    }
    ?>
@endsection
