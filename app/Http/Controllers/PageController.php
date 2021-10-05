<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Functions;

class PageController extends Controller
{
    public function SinglePage(Request $request, $page = null, $id = null){
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user'])) return redirect('/');
        switch ($page) {
            case 'service':
                return view('single_service', [ 'content' => $page, 'id' => $id ]);
                break;
            case 'category':
                return view('category', [ 'content' => $page, 'id' => $id ]);
                break;
            case 'promotion':
                return view('single_promotion', [ 'content' => $page, 'id' => $id ]);
                break;
            case 'article':
                return view('single_article', [ 'content' => $page, 'id' => $id ]);
                break;
            default:
                return response()->view('errors.404', $request, 404);
        }
    }
    public function EditPage(Request $request, $page, $id){
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user'])) return redirect('/');
        if (!Functions::is_admin()) return redirect('/');
        switch ($page) {
            case 'service':
                return view('service_edit', [ 'id' => $id ]);
                break;
            case 'article':
                return view('article_edit', [ 'id' => $id ]);
                break;
            case 'performer':
                return view('performer_edit', [ 'id' => $id ]);
                break;
            default:
                return response()->view('errors.404', $request, 404);
        }
    }
    public function ListPage(Request $request, $page = null){
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user'])) return redirect('/');
        switch($page){
            case 'articles':
                return view('articles');
                break;
            case 'news':
                return view('news');
                break;
            case 'promotions':
                return view('promotions', ['mode'=>$request->input('mode')]);
                break;
            case 'performers':
                return view('performers');
                break;
            case 'disport':
                return view('disport');
                break;
            case 'users':
                if (Functions::is_admin())
                    return view('users');
                else
                    return response()->view('errors.404', $request, 404);
                break;
            case 'admin':
                return view('admin');
                break;
            default:
                return response()->view('errors.404', $request, 404);
        }
    }
    public function NewPage(Request $request, $page){
        if (Functions::is_admin()){
            switch ($page){
                case 'service':
                    return view('service_new', [ 'cat_id' => $request->input('cat') ]);
                    break;
                case 'performer':
                    return view('performer_new', [ 'cat_id' => $request->input('cat') ]);
                    break;
                default:
                    return response()->view('errors.404', $request, 404);
            }
        } else {
            return response()->view('errors.404', $request, 404);
        }
    }
}
