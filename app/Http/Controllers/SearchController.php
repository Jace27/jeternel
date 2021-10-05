<?php

namespace App\Http\Controllers;

use App\Functions;
use App\Models;
use Illuminate\Http\Request;
use function PHPUnit\Framework\matches;

class SearchController extends Controller
{
    public function Search(Request $request, $page){
        $search = $this->parse_search($request->input('search'));
        $response = [];
        $founded = [];
        switch ($page){
            case 'article':
                $objects = Models\articles::all();
                break;
            case 'new':
                $objects = Models\news::all();
                break;
            case 'promotion':
                $objects = Models\promotions::all();
                break;
            default:
                $objects = Models\services::withTrashed()->get();
        }

        // в первом цикле ищем только по названиям услуги - название в приоритете
        foreach ($objects as $object){
            if (method_exists($object, 'trashed')) {
                if ($object->trashed())
                    $object->trashed = true;
                else
                    $object->trashed = false;
            }
            if(mb_strpos(isset($object->name) ? $object->name : $object->title, $request->input('search')) === 0){
                array_push($response, $object);
            }
            $string = $this->get_formatted_title($page, $object);
            // ищем каждое отдельное слово из запроса
            foreach ($search as $s) {
                if (strpos(mb_strtolower($string), mb_strtolower($s)) !== false) {
                    // делаем проверку, чтобы одна запись не попала в ответ больше одного раза
                    $push = true;
                    foreach ($founded as $key => $found){
                        if ($founded[$key]['object']->id == $object->id){
                            $founded[$key]['matches'] += 1;
                            $push = false;
                        }
                    }
                    if ($push){
                        array_push($founded, ['object'=>$object, 'matches'=>1]);
                    }
                }
            }
        }

        // сортируем результаты по кол-ву совпадений
        if (count($founded) > 0) {
            for($i1 = 0; $i1 < count($founded); $i1++) {
                $f_max = 0;
                for($i2 = $i1; $i2 < count($founded); $i2++) {
                    if ($founded[$i2]['matches'] >= $founded[$f_max]['matches']) {
                        $f_max = $i2;
                    }
                }
                $temp = $founded[$i1];
                $founded[$i1] = $founded[$f_max];
                $founded[$f_max] = $temp;
            }
        }
        foreach ($founded as $found){
            $ok = true;
            foreach ($response as $res) {
                if($res->id == $found['object']->id){
                    $ok = false;
                }
            }
            if($ok) {
                array_push($response, $found['object']);
            }
        }
        $founded = [];

        // во втором цикле ищем по всем остальным полям
        foreach ($objects as $object){
            $string = $this->get_formatted_content($page, $object);
            foreach ($search as $s) {
                if (strpos(mb_strtolower($string), mb_strtolower($s)) !== false) {
                    $push = true;
                    foreach ($founded as $key => $found) {
                        if ($founded[$key]['object']->id == $object->id) {
                            $founded[$key]['matches'] += 1;
                            $push = false;
                        }
                    }
                    foreach ($response as $key => $found) {
                        if ($response[$key]->id == $object->id) {
                            $push = false;
                        }
                    }
                    if ($push) array_push($founded, ['object'=>$object,'matches'=>1]);
                }
            }
        }

        // сортируем результаты по кол-ву совпадений
        if (count($founded) > 0) {
            for($i1 = 0; $i1 < count($founded); $i1++) {
                $f_max = 0;
                for($i2 = $i1; $i2 < count($founded); $i2++) {
                    if ($founded[$i2]['matches'] >= $founded[$f_max]['matches']) {
                        $f_max = $i2;
                    }
                }
                $temp = $founded[$i1];
                $founded[$i1] = $founded[$f_max];
                $founded[$f_max] = $temp;
            }
        }
        foreach ($founded as $found){
            array_push($response, $found['object']);
        }

        return [ "is_admin" => Functions::is_admin(), "data" => $response ];
    }

    // знаки препинания для удаления
    private $signs = [
        '-', '—', ',', '.', '!',
        '?', ':', ';', '(', ')',
        '[', ']', '{', '}', '<',
        '>', '"', '«', '»', '\'',
    ];

    private function parse_search($search){
        // удаляем повторящиеся знаки
        $temp = preg_split('//u', $search);
        $search = [];
        foreach($temp as $key => $char){
            if ($key > 0 && $temp[$key - 1] != $char){
                array_push($search, $char);
            }
        }
        $search = implode($search);
        // в запросе заменяем знаки препинания на пробелы
        $search = str_replace($this->signs, ' ', $search);
        // удаляем лишние пробелы
        while (strpos($search, '  '))
            $search = str_replace('  ', ' ', $search);
        // делим запрос на отдельные слова
        return explode(' ', trim($search));
    }

    private function get_formatted_title($page, $object){
        switch ($page){
            case 'article':
                // очищаем название от лишних знаков
                $string = strip_tags($object->name);
                break;
            case 'new':
            case 'promotion':
                // очищаем название от лишних знаков
                $string = strip_tags($object->title);
                break;
            default:
                // очищаем название от лишних знаков
                $string = strip_tags($object->name);
                // достаем все возможные другие названия
                $other_names = $object->other_names()->get();
                foreach ($other_names as $other_name)
                    $string = $string.' '.strip_tags($other_name->other_name);
        }
        $string = str_replace($this->signs, ' ', $string);
        $string = str_replace('́', '', $string);
        // прибираем мусор, удаляем все повторяющиеся знаки (аффинити = афинити)
        $temp = preg_split('//u', $string);
        $string = [];
        foreach($temp as $key => $char){
            if ($key > 0 && $temp[$key - 1] != $char){
                array_push($string, $char);
            }
        }
        $string = implode($string);
        return $string;
    }

    private function get_formatted_content($page, $object){
        switch ($page){
            case 'article':
            case 'new':
                $string = str_replace($this->signs, ' ', strip_tags($object->content));
                $object->content = mb_substr(strip_tags($object->content), 0, 250).'...';
                break;
            case 'promotion':
                $string = str_replace($this->signs, ' ', strip_tags($object->description));
                $object->description = mb_substr(strip_tags($object->description), 0, 250).'...';
                break;
            default:
                // объединяем все поля в одно, очищаем от лишних знаков
                $string = str_replace($this->signs, ' ', strip_tags($object->description));
                $string = $string.' '.str_replace($this->signs, ' ', strip_tags($object->preparation));
                $string = $string.' '.str_replace($this->signs, ' ', strip_tags($object->rehabilitation));
                $string = $string.' '.str_replace($this->signs, ' ', strip_tags($object->contraindications));
                $string = $string.' '.str_replace($this->signs, ' ', strip_tags($object->indications));
                $object->description = mb_substr(strip_tags($object->description), 0, 250).'...';
        }
        $string = str_replace('́', '', $string);
        $temp = preg_split('//u', $string);
        $string = [];
        foreach($temp as $key => $char){
            if ($key > 0 && $temp[$key - 1] != $char){
                array_push($string, $char);
            }
        }
        $string = implode($string);
        return $string;
    }
}
