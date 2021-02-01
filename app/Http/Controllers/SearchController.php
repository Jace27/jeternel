<?php

namespace App\Http\Controllers;

use App\Models\services;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function Search(Request $request){
        // знаки препинания для удаления
        $signs = [ '-', '—', ',', '.', '!', '?', ':', ';', '(', ')', '[', ']', '{', '}', '<', '>', '"', '\'', '«', '»' ];
        // в запросе заменяем знаки препинания на пробелы
        $search = str_replace($signs, ' ', $request->input('search'));
        // удаляем лишние пробелы
        while (strpos($search, '  ')) $search = str_replace('  ', ' ', $search);
        // делим запрос на отдельные слова
        $search = explode(' ', $search);
        $response = [];
        $services = services::withTrashed()->get();

        // в первом цикле ищем только по названиям услуги - название в приоритете
        foreach ($services as $service){
            if ($service->trashed())
                $service->trashed = true;
            else
                $service->trashed = false;

            // очищаем название от лишних знаков
            $string = strip_tags($service->name);
            // достаем все возможные другие названия
            $other_names = $service->other_names()->get();
            foreach ($other_names as $other_name)
                $string = $string.' '.strip_tags($other_name->other_name);
            $string = str_replace($signs, ' ', $string);
            // прибираем мусор
            while (strpos($string, '  ')) $string = str_replace('  ', ' ', $string);

            // ищем каждое отдельное слово из запроса
            foreach ($search as $s) {
                if (strpos(mb_strtolower($string), mb_strtolower($s)) !== false) {
                    // делаем проверку, чтобы одна запись не попала в ответ больше одного раза
                    $push = true;
                    foreach ($response as $item) if ($item->id == $service->id) $push = false;
                    if ($push) array_push($response, $service);
                }
            }
        }
        // во втором цикле ищем по всем остальным полям
        foreach ($services as $service){
            // объединяем все поля в одно, очищаем от лишних знаков
            $string = str_replace($signs, ' ', strip_tags($service->description));
            $string = $string.' '.str_replace($signs, ' ', strip_tags($service->preparation));
            $string = $string.' '.str_replace($signs, ' ', strip_tags($service->rehabilitation));
            $string = $string.' '.str_replace($signs, ' ', strip_tags($service->contraindications));
            while (strpos($string, '  ')) $string = str_replace('  ', ' ', $string);

            foreach ($search as $s) {
                if (strpos(mb_strtolower($string), mb_strtolower($s)) !== false) {
                    $service->description = mb_substr(strip_tags($service->description), 0, 250).'...';
                    $push = true;
                    foreach ($response as $item) if ($item->id == $service->id) $push = false;
                    if ($push) array_push($response, $service);
                }
            }
        }
        return $response;
    }
}
