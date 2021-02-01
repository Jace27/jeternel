<?php
function isAdmin(){
    if (!isset($_SESSION)) session_start();
    if (isset($_SESSION['user'])) {
        $user = \App\Models\users::where('phone', $_SESSION['user'])->first();
        if ($user != null) {
            if ($user->role()->first()->name == 'Администратор') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function echo_list($list, $top = true){
    if (!$top) echo '<img src="/images/icons/arrow_down.png" class="open_arrow">';
    echo '<ul style="'.( $top ? 'display: block;' : 'display: none;' ).'">';
    foreach ($list as $item){
        if (($top && count($item->parent()->get()) == 0) || (!$top && count($item->parent()->get()) != 0)) {
            echo '<li>';
            echo '<a href="/category/'.$item->id.'">'.$item->name.'</a>';
            if (count($item->children()->get()) != 0) {
                echo_list($item->children()->get(), false);
            }
            echo '</li>';
        }
    }
    if ($top && isAdmin()){
        echo '<li><a href="#" class="btn-link btn-cat-new" type="button" data-toggle="modal" data-target="#CatNewModal"><img src="/images/icons/cat-new.svg">&nbsp;&nbsp;Создать новую</a></li>';
    }
    echo '</ul>';
}

