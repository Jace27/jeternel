@extends('layout')

<?php
$service = \App\Models\services::withTrashed()->where('id', $id);
$service_exist = false;
if ($service->first() == null){
    $service = new \App\Models\services();
    $service->name = 'Услуги не существует';
} else {
    $service = $service->first();
    $service_exist = true;
}
?>

@section('title')
{{ $service->name }} - {{ \App\Settings::$title_site_name }} @endsection

@section('body')
    @if (!$service_exist)
        <h3>Услуги не существует</h3>
    @else
        <h2>{{ $service->name }} @if ($service->trashed()) - <span style="color: rgba(225, 25, 25, 0.75)">услуга больше не оказывается</span> @endif </h2>
        @if(\App\Functions::is_admin()) <button class="btn btn-outline-primary" onclick="window.location.assign('/service/{{ $service->id }}/edit');">Редактировать услугу</button> @endif
        <div>
            <b>Другие названия:</b>
            <div class="value">
                <?php
                $names = $service->other_names()->get();
                if (count($names) == 0)
                    echo 'Нет';
                else
                    foreach ($names as $name){
                        echo $name->other_name.'<br>';
                    }
                ?>
            </div>
        </div>
        <div>
            <div><b>Информирование о цене:</b><br>{!! $service->instruct1 !!}</div>
            <div class="mb-1 mt-1 p-1 w-50">
                <table>
                    <tbody>
                    <tr>
                        <td>Филиалы, косметолог:</td>
                        <td><div class="value d-inline-block">{{ $service->price()->first()->nonvip_low }}</div> руб.</td>
                    </tr>
                    <tr>
                        <td>Филиалы, врач:</td>
                        <td><div class="value d-inline-block">{{ $service->price()->first()->nonvip_high }}</div> руб.</td>
                    </tr>
                    <tr>
                        <td>В13, косметолог:</td>
                        <td><div class="value d-inline-block">{{ $service->price()->first()->vip_low }}</div> руб.</td>
                    </tr>
                    <tr>
                        <td>В13, врач:</td>
                        <td><div class="value d-inline-block">{{ $service->price()->first()->vip_high }}</div> руб.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div>{!! $service->instruct2 !!}</div>
        </div>
        <div>
            <b>Сопутствующие акции:</b><br>
            @if (count($service->promotions()->get()) == 0)
                Отсутствуют
            @else
                <div class="d-grid" style="grid-template-columns: 1fr 1fr 1fr">
                    <?php
                    $promotions = $service->promotions()->get();
                    $promotions_filtered = [];
                    foreach ($promotions as $promotion){
                        if ($promotion->promotion()->first()->end === null ||
                            (strtotime($promotion->promotion()->first()->start) <= time() &&
                             strtotime($promotion->promotion()->first()->end) >= time())){
                            array_push($promotions_filtered, $promotion);
                        } else {
                            //$promotion->delete();
                        }
                    }
                    if (count($promotions_filtered) == 0) echo 'Отсутствуют';
                    ?>
                    @foreach($promotions_filtered as $prom)
                        <div>
                            <a target="_blank" href="/promotion/{{ $prom->promotion_id }}"><img src="/images/promotions/{{ $prom->promotion()->first()->banner_file }}"></a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div>
            <b>Описание:</b><br>
            <div class="value"><?php $v = trim($service->description); ?>{!! $v != '' && $v != '&nbsp;' ? $service->description : 'Отсутствует' !!}</div>
        </div>
        <div>
            <b>Подготовка:</b><br>
            <div class="value"><?php $v = trim($service->preparation); ?>{!! $v != '' && $v != '&nbsp;' ? $service->preparation : 'Отсутствует' !!}</div>
        </div>
        <div>
            <b>Реабилитация:</b><br>
            <div class="value"><?php $v = trim($service->rehabilitation); ?>{!! $v != '' && $v != '&nbsp;' ? $service->rehabilitation : 'Отсутствует' !!}</div>
        </div>
        <div>
            <b>Показания:</b><br>
            <div class="value"><?php $v = trim($service->indications); ?>{!! $v != '' && $v != '&nbsp;' ? $service->indications : 'Отсутствуют' !!}</div>
        </div>
        <div>
            <b>Противопоказания:</b><br>
            <div class="value"><?php $v = trim($service->contraindications); ?>{!! $v != '' && $v != '&nbsp;' ? $service->contraindications : 'Отсутствуют' !!}</div>
        </div>
        <div>
            <b>Курс:</b><br>
            <div class="value"><?php $v = trim($service->course); ?>{!! $v != '' && $v != '&nbsp;' ? $service->course : 'Отсутствует' !!}</div>
        </div>
        <div>
            <b>Используемые препараты:</b><br>
            @if (count($service->drugs()->get()) == 0)
                Отсутствуют
            @else
                <table class="table">
                    <tbody>
                    <tr>
                        <th>Название</th>
                        <th>Производитель</th>
                        <th>Используемый объем</th>
                    </tr>
                    @foreach($service->drugs()->get() as $drug)
                        <tr>
                            <td>{{ $drug->drug()->first()->name }}</td>
                            <td>{{ $drug->drug()->first()->manufacturer }}</td>
                            <td>{{ $drug->using_volume }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div>
            <b>Осуществляют:</b><br>
            @if (count($service->performers()->get()) == 0)
                Отсутствуют
            @else
                <table class="table">
                    <tbody>
                    <tr>
                        <th style="width: 16%">Филиалы</th>
                        <th style="width: 16%">Специалист</th>
                        <th style="width: 36%">Представление</th>
                        <th style="width: 16%">Время выполнения процедуры</th>
                        <th style="width: 16%">График работы</th>
                    </tr>
                    @foreach(\App\Models\branches::all() as $branch)
                        @if(count($branch->performers()->get()) > 0)
                            @foreach($branch->performers()->get()->sortBy('last_name') as $performer)
                                @php
                                    $paste = false;
                                @endphp
                                @foreach($service->performers()->get() as $sperformer)
                                    @if($sperformer->performer()->first()->id == $performer->id)
                                        @php
                                            $paste = true;
                                        @endphp
                                    @endif
                                @endforeach
                                @if($paste)
                                    <tr>
                                        <td>
                                            <span>{{ $branch->address }} {{ $branch->name }}</span>
                                        </td>
                                        <td>
                                            <b>{{ $performer->last_name }} {{ $performer->first_name }} {{ $performer->second_name }}</b>
                                            <br>
                                            Специализация: {{ $performer->type()->first()->name }}
                                            <br>
                                            @foreach($performer->statuses()->get() as $status)
                                                @if($status->status_id != 1 && ($status->end === null || strtotime($status->end) >= time()) && ($status->start === null || strtotime($status->start) <= time()))
                                                    {{ $status->status()->first()->name }}
                                                    @if($status->start !== null)
                                                        c {{ date('d.m.Y', strtotime($status->start)) }}
                                                    @endif
                                                    @if($status->end !== null)
                                                        до {{ date('d.m.Y', strtotime($status->end)) }}
                                                    @endif
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{!! $performer->presentation !!}</td>
                                        <td>
                                            @if($performer->service_duration($id) != '00:00:00' && $performer->service_duration($id) != null)
                                                {{ $performer->service_duration($id) }}
                                            @endif
                                        </td>
                                        <td>{!! $performer->working_hours !!}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                    @foreach($service->performers()->get() as $performer)
                        @if(count($performer->performer()->first()->branches()->get()) == 0)
                            <tr>
                                <td>
                                    <span>Филиал не указан</span>
                                </td>
                                <td>
                                    <b>{{ $performer->performer()->first()->last_name }} {{ $performer->performer()->first()->first_name }} {{ $performer->performer()->first()->second_name }}</b>
                                    <br>
                                    Специализация: {{ $performer->performer()->first()->type()->first()->name }}
                                    <br>
                                    @foreach($performer->performer()->first()->statuses()->get() as $status)
                                        @if($status->status_id != 1 && ($status->end === null || strtotime($status->end) >= time()) && ($status->start === null || strtotime($status->start) <= time()))
                                            {{ $status->status()->first()->name }}
                                            @if($status->start !== null)
                                                c {{ date('d.m.Y', strtotime($status->start)) }}
                                            @endif
                                            @if($status->end !== null)
                                                до {{ date('d.m.Y', strtotime($status->end)) }}
                                            @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>{!! $performer->performer()->first()->presentation !!}</td>
                                <td>
                                    @if($performer->performer()->first()->service_duration($id) != '00:00:00' && $performer->performer()->first()->service_duration($id) != null)
                                        {{ $performer->performer()->first()->service_duration($id) }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif
@endsection
