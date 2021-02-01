<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function SinglePage(Request $request, $page, $id){
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
            default:
                return response()->view('errors.404', $request, 404);
        }
    }
    public function ListPage(Request $request, $page){
        switch($page){
            case 'articles':
                return view('articles');
                break;
            case 'news':
                return view('news');
                break;
            case 'promotions':
                return view('promotions');
                break;
            case 'disport':
                return view('disport');
                break;
            default:
                return response()->view('errors.404', $request, 404);
        }
    }
}
