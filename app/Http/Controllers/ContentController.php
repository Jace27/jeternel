<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Functions;

class ContentController extends Controller
{
    public function GetJSON(Request $request, $content, $id){
        if (Functions::is_admin()) {
            $content = $this->Get($content, $id);
            if ($content instanceof \App\Models\performers){
                $content->branches = $content->branches()->get();
            }
            if ($content == '404' || $content == null)
                return ['status' => 'error', 'message' => 'Объект не найден'];

            return ['status'=>'found', 'object'=>$content];
        } else {
            return ['status' => 'error', 'message' => 'Недостаточно прав'];
        }
    }
    public function Add(Request $request, $_content)
    {
        if (Functions::is_admin()) {
            $content = $this->Get($_content);
            if ($content == '404' || $content == null)
                return ['status' => 'error', 'message' => 'Объект не найден'];

            $fields = iterator_to_array($request->request);
            $non_resolved_fields = [];
            foreach ($fields as $key => $field) {
                if (mb_strtolower($field) == 'null')
                    unset($fields[$key]);
                else if ($key == 'phone')
                    $fields[$key] = mb_substr($field, mb_strlen($field) - 10, 10);
                else if ($_content == 'service' && $key == 'name'){
                    $vals = \App\Models\services::withTrashed()->where('name', $field)->get();
                    if (count($vals) > 0){
                        return ['status'=>'error', 'message'=>'Услуга уже существует, выберите другое название.'];
                    }
                } else if ($key == 'price_nonvip_low' || $key == 'price_nonvip_high' ||
                    $key == 'price_vip_low' || $key == 'price_vip_high') {
                    if (isset($fields['price_nonvip_low']) && isset($fields['price_nonvip_high']) &&
                        isset($fields['price_vip_low']) && isset($fields['price_vip_high'])) {
                        $price = \App\Models\prices::create([
                            'nonvip_low' => $fields['price_nonvip_low'],
                            'nonvip_high' => $fields['price_nonvip_high'],
                            'vip_low' => $fields['price_vip_low'],
                            'vip_high' => $fields['price_vip_high']
                        ]);
                        $fields['price_id'] = $price->id;
                        unset($fields['price_nonvip_low']);
                        unset($fields['price_nonvip_high']);
                        unset($fields['price_vip_low']);
                        unset($fields['price_vip_high']);
                    }
                } else if ($key == 'class_categories') {
                    $non_resolved_fields[$key] = $field;
                    unset($fields[$key]);
                } else if ($key == 'trouble_categories') {
                    $non_resolved_fields[$key] = $field;
                    unset($fields[$key]);
                } else if ($key == 'other_names') {
                    $non_resolved_fields[$key] = $field;
                    unset($fields[$key]);
                } else if ($key == 'drugs') {
                    $non_resolved_fields[$key] = $field;
                    unset($fields[$key]);
                } else if ($key == 'performers') {
                    $non_resolved_fields[$key] = $field;
                    unset($fields[$key]);
                } else if ($key == 'branches') {
                    $non_resolved_fields[$key] = $field;
                    unset($fields[$key]);
                } else if ($key == 'promotions') {
                    $non_resolved_fields[$key] = $field;
                    unset($fields[$key]);
                }
            }

            $content = $content::create($fields);

            if (isset($non_resolved_fields['class_categories'])) {
                $vals = json_decode($non_resolved_fields['class_categories']);
                foreach ($vals as $key => $val) {
                    \App\Models\service_service_categories::create([
                        'service_id'  => $content->id,
                        'category_id' => $vals[$key]
                    ]);
                }
                unset($non_resolved_fields['class_categories']);
            }
            if (isset($non_resolved_fields['trouble_categories'])) {
                $vals = json_decode($non_resolved_fields['trouble_categories']);
                foreach ($vals as $key => $val) {
                    \App\Models\service_service_categories::create([
                        'service_id'  => $content->id,
                        'category_id' => $vals[$key]
                    ]);
                }
                unset($non_resolved_fields['trouble_categories']);
            }
            if (isset($non_resolved_fields['other_names'])) {
                $vals = json_decode($non_resolved_fields['other_names']);
                foreach ($vals as $key => $val){
                    $vals[$key] = [
                        'service_id' => $content->id,
                        'other_name' => $val
                    ];
                }
                \App\Models\service_other_names::insert($vals);
                unset($non_resolved_fields['other_names']);
            }
            if (isset($non_resolved_fields['drugs'])) {
                $vals = json_decode($non_resolved_fields['drugs']);
                foreach ($vals as $key => $val){
                    $vals[$key] = [
                        'service_id'   => $content->id,
                        'drug_id'      => $val->id,
                        'using_volume' => $val->volume
                    ];
                }
                \App\Models\service_drugs::insert($vals);
                unset($non_resolved_fields['drugs']);
            }
            if (isset($non_resolved_fields['performers'])) {
                $vals = json_decode($non_resolved_fields['performers']);
                foreach ($vals as $key => $val){
                    $vals[$key] = [
                        'service_id'   => $content->id,
                        'performer_id' => $val->id,
                        'duration'     => $val->time
                    ];
                }
                \App\Models\service_performers::insert($vals);
                unset($non_resolved_fields['performers']);
            }
            if (isset($non_resolved_fields['branches'])) {
                $vals = json_decode($non_resolved_fields['branches']);
                foreach ($vals as $key => $val){
                    $vals[$key] = [
                        'performer_id'  => $content->id,
                        'branch_id'     => $val
                    ];
                }
                \App\Models\performers_branches::insert($vals);
                unset($non_resolved_fields['branches']);
            }
            if (isset($non_resolved_fields['promotions'])) {
                $vals = json_decode($non_resolved_fields['promotions']);
                foreach ($vals as $key => $val){
                    $vals[$key] = [
                        'service_id'    => $content->id,
                        'promotion_id'  => $val
                    ];
                }
                \App\Models\service_promotions::insert($vals);
                unset($non_resolved_fields['promotions']);
            }

            return ['status' => 'success', 'id' => $content->id];
        } else {
            return ['status' => 'error', 'message' => 'Недостаточно прав'];
        }
    }

    public function EditField(Request $request, $content, $id)
    {
        if (Functions::is_admin()) {
            if ($request->input('field') == 'role_id' && $content == 'user'){
                $admin_id = \App\Models\roles::where('name', 'Администратор')->first()->id;
                if (count(\App\Models\users::where('role_id', $admin_id)->get()) === 1 && $request->input('value') != $admin_id){
                    return ['status' => 'error', 'message' => 'В системе должен быть хотя бы один администратор'];
                }
            }

            $content = $this->Get($content, $id);
            if ($content == '404' || $content == null)
                return ['status' => 'error', 'message' => 'Объект не найден'];

            $field = $request->input('field');
            if (in_array($field, $content->getColumnsNames())) {
                $content->$field = mb_strtolower($request->input('value')) != 'null' ? $request->input('value') : null;
                $content->save();
            } else {
                return ['status' => 'error', 'message' => 'Поле не существует'];
            }

            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'message' => 'Недостаточно прав'];
        }
    }
    public function Edit(Request $request, $_content, $id){
        if (Functions::is_admin()){
            $content = $this->Get($_content, $id);
            if ($content == '404' || $content == null)
                return ['status' => 'error', 'message' => 'Объект не найден'];

            $fields = iterator_to_array($request->request);
            $non_resolved_fields = [];
            foreach ($fields as $key => $field) {
                if (mb_strtolower($field) == 'null')
                    unset($fields[$key]);
                else if ($key == 'phone')
                    $fields[$key] = mb_substr($field, mb_strlen($field) - 10, 10);
                else if ($_content == 'service' && $key == 'name') {
                    $vals = \App\Models\services::withTrashed()->where('name', $field)->get();
                    $ok = false;
                    foreach ($vals as $val) {
                        if ($val->id == $content->id)
                            $ok = true;
                    }
                    if (count($vals) > 0 && !$ok) {
                        return ['status' => 'error', 'message' => 'Услуга уже существует, выберите другое название.'];
                    } else {
                        $content->$key = mb_strtolower($fields[$key]) != 'null' ? $fields[$key] : null;
                        $content->save();
                    }
                } else if ($key == 'price_nonvip_low' || $key == 'price_nonvip_high' ||
                    $key == 'price_vip_low' || $key == 'price_vip_high') {
                    if (isset($fields['price_nonvip_low']) && isset($fields['price_nonvip_high']) &&
                        isset($fields['price_vip_low']) && isset($fields['price_vip_high'])) {
                        $price = $content->price()->first();
                        $price->nonvip_low = $fields['price_nonvip_low'];
                        unset($fields['price_nonvip_low']);
                        $price->nonvip_high = $fields['price_nonvip_high'];
                        unset($fields['price_nonvip_high']);
                        $price->vip_low = $fields['price_vip_low'];
                        unset($fields['price_vip_low']);
                        $price->vip_high = $fields['price_vip_high'];
                        unset($fields['price_vip_high']);
                        $price->save();
                    }
                } else if ($key == 'class_categories') {
                    $non_resolved_fields[$key] = $field;
                    unset($fields[$key]);
                } else if ($key == 'trouble_categories') {
                    $non_resolved_fields[$key] = $field;
                    unset($fields[$key]);
                } else if ($key == 'other_names') {
                    $non_resolved_fields[$key] = $field;
                    unset($fields[$key]);
                } else if ($key == 'drugs') {
                    $non_resolved_fields[$key] = $field;
                    unset($fields[$key]);
                } else if ($key == 'performers') {
                    $non_resolved_fields[$key] = $field;
                    unset($fields[$key]);
                } else if ($key == 'branches') {
                    $non_resolved_fields[$key] = $field;
                    unset($fields[$key]);
                } else if ($key == 'promotions') {
                    $non_resolved_fields[$key] = $field;
                    unset($fields[$key]);
                } else if ($_content == 'article_section' && $key == 'parent_section_id'){
                    if ($content->id == $field){
                        unset($fields[$key]);
                    } else {
                        $content->$key = mb_strtolower($fields[$key]) != 'null' ? $fields[$key] : null;
                        $content->save();
                    }
                } else {
                    if (in_array($key, $content->getColumnsNames())) {
                        $content->$key = mb_strtolower($fields[$key]) != 'null' ? $fields[$key] : null;
                        $content->save();
                    }
                }
            }

            if (isset($non_resolved_fields['class_categories'])) {
                $vals = json_decode($non_resolved_fields['class_categories']);
                $fields = \App\Models\service_service_categories::where('service_id', $content->id)->get();
                foreach ($fields as $field){
                    if ($field->category()->first()->type()->first()->name == 'По классу') {
                        $field->delete();
                    }
                }
                foreach ($vals as $key => $val){
                    $vals[$key] = [
                        'service_id' => $content->id,
                        'category_id' => $val
                    ];
                }
                \App\Models\service_service_categories::insert($vals);
                unset($non_resolved_fields['class_categories']);
            }
            if (isset($non_resolved_fields['trouble_categories'])) {
                $vals = json_decode($non_resolved_fields['trouble_categories']);
                $fields = \App\Models\service_service_categories::where('service_id', $content->id)->get();
                foreach ($fields as $field){
                    if ($field->category()->first()->type()->first()->name == 'По проблемам клиента') {
                        $field->delete();
                    }
                }
                foreach ($vals as $key => $val){
                    $vals[$key] = [
                        'service_id' => $content->id,
                        'category_id' => $val
                    ];
                }
                \App\Models\service_service_categories::insert($vals);
                unset($non_resolved_fields['trouble_categories']);
            }
            if (isset($non_resolved_fields['other_names'])) {
                $vals = json_decode($non_resolved_fields['other_names']);
                $fields = \App\Models\service_other_names::where('service_id', $content->id)->get();
                foreach ($fields as $field){
                    $field->delete();
                }
                foreach ($vals as $key => $val){
                    $vals[$key] = [
                        'service_id' => $content->id,
                        'other_name' => $val
                    ];
                }
                \App\Models\service_other_names::insert($vals);
                unset($non_resolved_fields['other_names']);
            }
            if (isset($non_resolved_fields['drugs'])) {
                $vals = json_decode($non_resolved_fields['drugs']);
                $fields = \App\Models\service_drugs::where('service_id', $content->id)->get();
                foreach ($fields as $key => $field){
                    $fields[$key]->delete();
                }
                foreach ($vals as $key => $val){
                    $vals[$key] = [
                        'service_id'   => $content->id,
                        'drug_id'      => $val->id,
                        'using_volume' => $val->volume
                    ];
                }
                \App\Models\service_drugs::insert($vals);
                unset($non_resolved_fields['drugs']);
            }
            if (isset($non_resolved_fields['performers'])) {
                $vals = json_decode($non_resolved_fields['performers']);
                $fields = \App\Models\service_performers::where('service_id', $content->id)->get();
                foreach ($fields as $key => $field){
                    $fields[$key]->delete();
                }
                foreach ($vals as $key => $val){
                    $vals[$key] = [
                        'service_id'   => $content->id,
                        'performer_id' => $val->id,
                        'duration'     => $val->time
                    ];
                }
                \App\Models\service_performers::insert($vals);
                unset($non_resolved_fields['performers']);
            }
            if (isset($non_resolved_fields['branches'])) {
                $vals = json_decode($non_resolved_fields['branches']);
                $fields = \App\Models\performers_branches::where('performer_id', $content->id)->get();
                foreach ($fields as $key => $field){
                    $fields[$key]->delete();
                }
                foreach ($vals as $key => $val){
                    $vals[$key] = [
                        'performer_id' => $content->id,
                        'branch_id'    => $val
                    ];
                }
                \App\Models\performers_branches::insert($vals);
                unset($non_resolved_fields['branches']);
            }
            if (isset($non_resolved_fields['promotions'])) {
                $vals = json_decode($non_resolved_fields['promotions']);
                $fields = \App\Models\service_promotions::where('service_id', $content->id)->get();
                foreach ($fields as $key => $field){
                    $fields[$key]->delete();
                }
                foreach ($vals as $key => $val){
                    $vals[$key] = [
                        'service_id'    => $content->id,
                        'promotion_id'  => $val
                    ];
                }
                \App\Models\service_promotions::insert($vals);
                unset($non_resolved_fields['promotions']);
            }

            return ['status' => 'success', 'id' => $content->id];
        } else {
            return ['status' => 'error', 'message' => 'Недостаточно прав'];
        }
    }

    public function Delete(Request $request, $content, $id = null)
    {
        if (Functions::is_admin()) {
            $id_array = [];
            if ($id != null) {
                array_push($id_array, $id);
            } else {
                $id_array = json_decode($request->input('id_array'));
            }

            foreach ($id_array as $ida){
                $obj = $this->Get($content, $ida);
                if ($obj != '404' && $obj != null) {
                    //return ['status' => 'error', 'message' => 'Объект не найден'];
                    if($content == 'article_section'){
                        $linked = \App\Models\articles::where('section_id', $obj->id)->get();
                        foreach ($linked as $lobj){
                            $lobj->section_id = null;
                            $lobj->save();
                        }
                    }
                    $obj->delete();
                }
            }
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'message' => 'Недостаточно прав'];
        }
    }
    public function Restore(Request $request, $content, $id = null)
    {
        if (Functions::is_admin()) {
            $id_array = [];
            if ($id != null) {
                array_push($id_array, $id);
            } else {
                foreach (json_decode($request->input('id_array')) as $id){
                    array_push($id_array, $id);
                }
            }

            foreach ($id_array as $id){
                $content = $this->Get($content, $id);
                if ($content != '404' && $content != null && $content->trashed())
                    $content = $content->restore();
            }
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'message' => 'Недостаточно прав'];
        }
    }

    public function PerformerStatusSet(Request $request, $id){
        if (Functions::is_admin()){
            $performer = $this->Get('performer', $id);
            if ($performer != null){
                $statuses = \App\Models\performers_performers_statuses::where('performer_id', $id)->get();
                foreach ($statuses as $status){
                    $status->delete();
                }
                \App\Models\performers_performers_statuses::create([
                    'performer_id'  => $request->input('performer_id'),
                    'status_id'     => $request->input('status_id'),
                    'start'         => $request->input('start') != 'null' ? $request->input('start') : null,
                    'end'           => $request->input('end') != 'null' ? $request->input('end') : null
                ]);
                return ['status'=>'success'];
            } else {
                return ['status'=>'error', 'message'=>'Специалист не найден'];
            }
        } else {
            return ['status'=>'error', 'message'=>'Недостаточно прав'];
        }
    }

    public function ServiceMove(Request $request, $id){
        if (Functions::is_admin()){
            if ($request->input('cat_from') != 0) {
                $object = \App\Models\service_service_categories::where([
                    ['service_id', '=', $id],
                    ['category_id', '=', $request->input('cat_from')]
                ])->first();
            } else {
                $object = new \App\Models\service_service_categories();
                $object->service_id = $id;
            }
            if ($request->input('cat_to') != 0)
                $object->category_id = $request->input('cat_to');
            else if ($request->input('cat_from') != 0)
                $object->delete();
            if ($request->input('cat_from') == 0)
                $object->save();
            return ['status'=>'success'];
        } else {
            return ['status'=>'error', 'message'=>'Недостаточно прав'];
        }
    }

    private function Get($content, $id = null){
        switch ($content) {
            case 'service':
                if ($id == null)
                    return \App\Models\services::class;
                else
                    return \App\Models\services::withTrashed()->where('id', $id)->first();
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
            case 'service_category':
                if ($id == null)
                    return \App\Models\service_service_categories::class;
                else
                    return \App\Models\service_service_categories::where('id', $id)->first();
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

    public function PromSpecialEdit(Request $request){
        \Illuminate\Support\Facades\Storage::disk('local')->put('promotion_special.json', json_encode($request->input()));
        return [ "status" => "saved" ];
    }
}
