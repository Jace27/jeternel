@extends('layout')

@section('title')
{{ \App\Settings::$title_site_name }} @endsection

@section('body')
    <div class="d-grid" style="grid-template-columns: 1fr">

        <div class="align-center">
            <table class="table">
                <tbody>
                <tr>
                    <th>Важная информация:</th>
                </tr>
                @foreach(\App\Models\news::where('is_important', 1)->orderBy('updated_at', 'desc')->get() as $new)
                    <tr>
                        <td>
                            <b class="important">{{ $new->title }}</b> <span style="color: #666">{{ date('H:i d.m.Y', strtotime($new->updated_at) + 5*60*60) }}</span><br>
                            <p>{!! $new->content !!}</p>
                        </td>
                    </tr>
                @endforeach
                @foreach(\App\Models\news::where('is_important', 0)->orderBy('updated_at', 'desc')->get() as $new)
                    <tr>
                        <td>
                            <b>{{ $new->title }}</b> <span style="color: #666">{{ date('H:i d.m.Y', strtotime($new->updated_at)+5*60*60) }}</span><br>
                            <p>{!! $new->content !!}</p>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
