<?php


namespace App;


class Functions
{
    public static function is_admin(){
        if (!isset($_SESSION)) session_start();
        if (isset($_SESSION['user'])) {
            $user = \App\Models\users::where('phone', $_SESSION['user'])->first();
            if ($user != null) {
                if ($user->role()->first()->name == 'Администратор' || $user->role()->first()->name == 'Разработчик') {
                    return true;
                }
            }
        }
        return false;
    }
    public static function echo_menu_list($list, $top = true)
    {
        if (!$top) echo '<img src="/images/icons/arrow_down.png" class="open_arrow">';
        echo '<ul style="' . ($top ? 'display: block;' : 'display: none;') . '">';
        if ($top) {
            echo '<li><div class="menu_item_header"><a href="/category/0">Услуги без категории</a></div></li>';
        }
        foreach ($list as $item) {
            if (($top && count($item->parent()->get()) == 0) || (!$top && count($item->parent()->get()) != 0)) {
                echo '<li>';
                echo '<div class="menu_item_header">';
                echo '<a href="/category/' . $item->id . '">' . $item->name . '</a>';
                echo '</div>';
                if (count($item->children()->get()) != 0) {
                    \App\Functions::echo_menu_list($item->children()->get(), false);
                }
                echo '</li>';
            }
        }
        if ($top && Functions::is_admin()) {
            echo '<li><div class="menu_item_header"><a href="#" class="btn-link btn-cat-new" type="button" data-toggle="modal" data-target="#CatNewModal"><img src="/images/icons/cat-new.svg">&nbsp;&nbsp;Создать новую</a></div></li>';
        }
        echo '</ul>';
    }
    public static function translate_month($month){
        switch ($month){
            case 'January':
                $month = 'Январь';
                break;
            case 'February':
                $month = 'Февраль';
                break;
            case 'March':
                $month = 'Март';
                break;
            case 'April':
                $month = 'Апрель';
                break;
            case 'May':
                $month = 'Май';
                break;
            case 'June':
                $month = 'Июнь';
                break;
            case 'July':
                $month = 'Июль';
                break;
            case 'August':
                $month = 'Август';
                break;
            case 'September':
                $month = 'Сентябрь';
                break;
            case 'October':
                $month = 'Октябрь';
                break;
            case 'November':
                $month = 'Ноябрь';
                break;
            case 'December':
                $month = 'Декабрь';
                break;
            default:
                $month = 'Неизвестно';
        }
        return $month;
    }
}
