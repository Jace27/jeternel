@extends('layout')

@section('title')
Скрипты - База знаний Jeternel @endsection

@section('body')
    <?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/../functions.php';

        $articles = \App\Models\articles_sections::where('parent_section_id', null)->get();
        if (count($articles) == 0){
            echo '<h3>Скриптов нет</h3>';
        } else {
            echo '<h3>Скрипты</h3>';
            echo '<div class="articles_list">';
                echo_articles_list($articles);
            echo '</div>';
        }
    ?>
@endsection

<?php
function echo_articles_list($list, $top = true){
    if (!$top) echo '<img src="/images/icons/arrow_down.png" class="open_arrow">';
    echo '<ul style="'.( $top ? 'display: block;' : 'display: none;' ).'">';
    foreach ($list as $item){
        if (get_class($item) == 'App\Models\articles' ||
            (get_class($item) == 'App\Models\articles_sections' &&
                (($top && count($item->parent()->get()) == 0) ||
                    (!$top && count($item->parent()->get()) != 0)))) {
            echo '<li>';
            if (get_class($item) == 'App\Models\articles')
                echo '<a href="/article/'.$item->id.'">'.$item->name.'</a>';
            if (get_class($item) == 'App\Models\articles_sections')
                echo '<a href="/articles/section/'.$item->id.'">'.$item->name.'</a>';
            if (get_class($item) == 'App\Models\articles_sections' && count($item->children()->get()) != 0) {
                echo_articles_list($item->children()->get(), false);
            }
            if (get_class($item) == 'App\Models\articles_sections' && count($item->articles()->get()) != 0) {
                echo '<hr style="margin-top: 0.3rem; margin-bottom: 0.3rem; display: none;">';
                echo_articles_list($item->articles()->get(), false);
            }
            echo '</li>';
        }
    }
    if ($top && isAdmin()){
        //echo '<li><a href="#" class="btn-link btn-cat-new" type="button" data-toggle="modal" data-target="#CatNewModal"><img src="/images/icons/cat-new.svg">&nbsp;&nbsp;Создать новую</a></li>';
    }
    echo '</ul>';
}

?>
