@extends('layout')

@section('title')
Акции - База знаний Jeternel @endsection

@section('body')
    <?php
    $promotions = \App\Models\promotions::all();
    if (count($promotions) == 0){
        echo '<h3>Нет акций</h3>';
    } else {
        echo '<h3>Акции</h3>';
        foreach ($promotions as $promotion){
            echo '<p><a href="/promotion/'.$promotion->id.'"><b>'.$promotion->title.'</b></a><br>'.mb_substr(strip_tags($promotion->description), 0, 250).'...'.'</p><hr>';
        }
    }
    ?>
@endsection
