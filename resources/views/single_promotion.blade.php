@extends('layout')

<?php
$prom = \App\Models\promotions::where('id', $id);
$prom_exist = false;
if ($prom->first() == null){
    $prom = new \App\Models\promotions();
    $prom->name = 'Акции не существует';
} else {
    $prom = $prom->first();
    $prom_exist = true;
}
?>

@section('title')
{{ $prom->name }} - База знаний Jeternel @endsection

@section('body')
    <?php
    if (!$prom_exist){
        echo '<h3>Акции не существует</h3>';
    } else {
        ?>
        <h2>Акция: {{ $prom->title }}</h2>
        <div class="value">
            <img src="/images/promotions/{{ $prom->banner_file }}">
        </div>
        <div class="value">{!! $prom->description !!}</div>
        <div class="value">Начало: {{ $prom->start }}</div>
        <div class="value">Окончание: {{ $prom->end }}</div>
        <?php
    }
    ?>
@endsection
