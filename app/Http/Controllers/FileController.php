<?php

namespace App\Http\Controllers;

use App\Functions;
use App\Models\media;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function Upload(Request $request, $destination, $name = null){
        if (Functions::is_admin()){
            if (($files = $request->file()) != null){
                foreach ($files as $file) {
                    switch ($destination) {
                        case 'disport':
                            if (mb_strpos($file->getMimeType(), 'image/') != 0)
                                return ['status'=>'error', 'message'=>'Файл не является изображением'];
                            $file_name = '';
                            switch ($request->input('month')) {
                                case 'current':
                                    $file_name = date('Y-m-d His').'.png';
                                    break;
                                case 'next':
                                    $file_name = date('Y-m-d His', time()+60*60*24*30).'.png';
                                    break;
                                default:
                                    $file_name = date('Y-m-d His').'.png';
                            }
                            $file->move($_SERVER['DOCUMENT_ROOT'].'/images/disport/', $file_name);
                            break;
                        case 'banner':
                        case 'prom_type_a':
                        case 'prom_type_b':
                        case 'prom_type_c':
                            if (mb_strpos($file->getMimeType(), 'image/') != 0)
                                return ['status'=>'error', 'message'=>'Файл не является изображением'];
                            $file_name = (int)time();
                            while (file_exists($_SERVER['DOCUMENT_ROOT'].'/images/promotions/'.$file_name.'.png'))
                                $file_name += 1;
                            $file_name = (string)$file_name.'.png';
                            $file->move($_SERVER['DOCUMENT_ROOT'].'/images/promotions/', $file_name);
                            return ['status'=>'success','file_name'=>$file_name];
                            break;
                        case 'performer':
                            if (mb_strpos($file->getMimeType(), 'image/') != 0)
                                return ['status'=>'error', 'message'=>'Файл не является изображением'];
                            $file_name = (int)time();
                            while (file_exists($_SERVER['DOCUMENT_ROOT'].'/images/performers/'.$file_name.'.png'))
                                $file_name += 1;
                            $file_name = (string)$file_name.'.png';
                            $file->move($_SERVER['DOCUMENT_ROOT'].'/images/performers/', $file_name);
                            return ['status'=>'success','file_name'=>$file_name];
                            break;
                        case 'articles':
                            if (mb_strpos($file->getMimeType(), 'image/') != 0)
                                return ['status'=>'error', 'message'=>'Файл не является изображением'];
                            $file_name = (int)time();
                            while (file_exists($_SERVER['DOCUMENT_ROOT'].'/images/articles/'.$file_name.'.png'))
                                $file_name += 1;
                            $file_name = (string)$file_name.'.png';
                            $file->move($_SERVER['DOCUMENT_ROOT'].'/images/articles/', $file_name);
                            if ($name != null && is_string($name)) {
                                $entry = media::get($name);
                                if ($entry == null) {
                                    $media = new media();
                                    $media->name = $name;
                                    $media->file_name = $file_name;
                                    $media->page = 'articles';
                                    $media->save();
                                } else {
                                    unlink($_SERVER['DOCUMENT_ROOT'].'/images/articles/'.$entry->file_name);
                                    $entry->file_name = $file_name;
                                    if ($entry->trashed()) $entry->restore();
                                    $entry->save();
                                }
                            } else {
                                $media = new media();
                                $media->file_name = $file_name;
                                $media->page = 'articles';
                                $media->save();
                            }
                            return ['status'=>'success','file_name'=>$file_name];
                            break;
                        default:
                            return ['status' => 'error', 'message' => 'Некорректное расположение файла'];
                    }
                }
                return ['status'=>'success'];
            } else {
                return ['status'=>'error', 'message'=>'Не передано файлов для загрузки'];
            }
        } else {
            return ['status'=>'error', 'message'=>'Недостаточно прав'];
        }
    }
    public function Delete(Request $request, $destination, $file){
        if ($destination == 'articles'){
            $entry = media::get($file);
            if ($entry != null){
                $file = $entry->file_name;
                $entry->delete();
            }
        }
        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/images/'.$destination.'/'.urldecode($file))) {
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/'.$destination.'/'.urldecode($file));
            return ['status' => 'deleted'];
        } else {
            return ['status' => 'Файл не найден'];
        }
    }
    public function IsExist(Request $request, $name){
        $entry = media::withTrashed()->where('name', '=', $name)->first();
        if ($entry == null){
            return ['status'=>'not exist'];
        } else {
            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/images/articles/'.$entry->file_name)){
                return ['status'=>'exist'];
            } else {
                return ['status'=>'not exist'];
            }
        }
    }
    public function GetJSON(Request $request){
        $entries = media::withTrashed()->get();
        foreach ($entries as $entry){
            $entry->trashed = $entry->trashed();
        }
        return ['status'=>'success', 'objects'=>$entries];
    }
}
