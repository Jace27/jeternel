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
{{ $service->name }} - База знаний Jeternel @endsection

@section('body')
    <?php
    if (!$service_exist){
        echo '<h3>Услуги не существует</h3>';
    } else {
        ?>
        <h2>{{ $service->name }} <?php if ($service->trashed()) echo ' - <span style="color: rgba(225, 25, 25, 0.75)">услуга больше не оказывается</span>'; ?></h2>
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
            <b>Цена:</b> <div class="value d-inline-block">{{ $service->cost }}</div>руб.
        </div>
        <div>
            <b>Описание:</b><br>
            <div class="value">{!! $service->description ? $service->description : 'Отсутствует' !!}</div>
        </div>
        <div>
            <b>Подготовка:</b><br>
            <div class="value">{!! $service->preparation ? $service->preparation : 'Отсутствует' !!}</div>
        </div>
        <div>
            <b>Реабилитация:</b><br>
            <div class="value">{!! $service->rehabilitation ? $service->rehabilitation : 'Отсутствует' !!}</div>
        </div>
        <div>
            <b>Противопоказания:</b><br>
            <div class="value">{!! $service->contraindications ? $service->contraindications : 'Отсутствуют' !!}</div>
        </div>
        <div>
            <b>Продолжительность:</b><br>
            <div class="value">{!! $service->duration ? $service->duration : 'Отсутствует' !!}</div>
        </div>
        <div>
            <b>Курс:</b><br>
            <div class="value">{!! $service->course ? $service->course : 'Отсутствует' !!}</div>
        </div>
        <div>
            <b>Используемые препараты:</b><br>
            Отсутствуют
        </div>
        <div>
            <b>Осуществляют:</b><br>
            <?php
            $performers = $service->performers()->get();
            if (count($performers) == 0) {
                echo 'Отсутствуют';
            } else {
                ?>
                <table class="performers">
                    <tbody>
                    <tr>
                        <th>Имя</th>
                        <th>Специализация</th>
                        <th>Представление</th>
                        <th>Время выполнения процедуры</th>
                        <th>Филиал</th>
                    </tr>
                    <?php
                    foreach ($performers as $performer){
                    ?>
                    <tr>
                        <td><?php echo $performer->first_name.' '.$performer->third_name.' '.$performer->last_name; ?></td>
                        <td>{!! $performer->specialization !!}</td>
                        <td>{!! $performer->experience !!}</td>
                        <td>{{ $performer->service_duration($id) }}</td>
                        <td><?php if ($performer->branch()->first() != null) echo $performer->branch()->first()->name.', '.$performer->branch()->first()->address; ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
@endsection
