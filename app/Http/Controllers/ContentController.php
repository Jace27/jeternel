<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

require_once $_SERVER['DOCUMENT_ROOT'].'/../functions.php';

class ContentController extends Controller
{
    public function Add(Request $request, $content){
        $content = $this->Get($content);
        if ($content == '404' || $content == null)
            return [ 'status' => 'content not found' ];

        $fields = iterator_to_array($request->request);
        foreach ($fields as $key => $field)
            if (mb_strtolower($field) == 'null')
                unset($fields[$key]);
        $content = $content::create($fields);

        return [ 'status' => 'success', 'id' => $content->id ];
    }

    public function EditField(Request $request, $content, $id){
        $content = $this->Get($content, $id);
        if ($content == '404' || $content == null)
            return [ 'status' => 'content not found' ];

        $field = $request->input('field');
        if (in_array($field, $content->getColumnsNames())) {
            $content->$field = mb_strtolower($request->input('value')) != 'null' ? $request->input('value') : null;
            $content->save();
            return [ 'status' => 'success' ];
        } else {
            return [ 'status' => 'error', 'message' => 'field does not exist' ];
        }
    }

    public function Delete(Request $request, $content, $id){
        $content = $this->Get($content, $id);
        if ($content == '404' || $content == null)
            return [ 'status' => 'content not found' ];

        $content = $content->delete();
        return [ 'status' => 'success' ];
    }

    private function Get($content, $id = null){
        switch ($content) {
            case 'service':
                if ($id == null)
                    return \App\Models\services::class;
                else
                    return \App\Models\services::where('id', $id)->first();
            case 'service_other_name':
                if ($id == null)
                    return \App\Models\service_other_names::class;
                else
                    return \App\Models\service_other_names::where('id', $id)->first();
            case 'category':
                if ($id == null)
                    return \App\Models\service_categories::class;
                else
                    return \App\Models\service_categories::where('id', $id)->first();
            case 'promotion':
                if ($id == null)
                    return \App\Models\promotions::class;
                else
                    return \App\Models\promotions::where('id', $id)->first();
            case 'performer':
                if ($id == null)
                    return \App\Models\performers::class;
                else
                    return \App\Models\performers::where('id', $id)->first();
            case 'article':
                if ($id == null)
                    return \App\Models\articles::class;
                else
                    return \App\Models\articles::where('id', $id)->first();
            case 'article_section':
                if ($id == null)
                    return \App\Models\articles_sections::class;
                else
                    return \App\Models\articles_sections::where('id', $id)->first();
            case 'news':
                if ($id == null)
                    return  \App\Models\news::class;
                else
                    return \App\Models\news::where('id', $id)->first();
            case 'drug':
                if ($id == null)
                    return \App\Models\drugs::class;
                else
                    return \App\Models\drugs::where('id', $id)->first();
            case 'branch':
                if ($id == null)
                    return \App\Models\branches::class;
                else
                    return \App\Models\branches::where('id', $id)->first();
            case 'user':
                if ($id == null)
                    return \App\Models\users::class;
                else
                    return \App\Models\users::where('id', $id)->first();
            default:
                return '404';
        }
    }
}
