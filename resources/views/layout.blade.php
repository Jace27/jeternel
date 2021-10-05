@include('includes.preload')
<!doctype html>
<html lang="ru">
<head>
    @include('includes.head')
    <title>@yield('title')</title>
</head>
<body>

<table class="window">
    <tbody>

    @include('includes.header')
    <tr>
        <td class="menu">@include('includes.left_menu')</td>
        <td class="align-top" style="min-width: 400px;">
            @include('includes.message')

            <div class="body">
                @if($_SERVER['REQUEST_URI'] !== '/news' && $_SERVER['REQUEST_URI'] !== '/')
                <div class="news">
                    @php $i = 0; @endphp
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>Важная информация:</th>
                        </tr>
                            @foreach(\App\Models\news::where('is_important', 1)->orderBy('updated_at', 'desc')->get() as $new)
                                <tr>
                                    <td>
                                        <b>{{ $new->title }}</b> {{ date('H:i:s d.m.Y', strtotime($new->updated_at)+5*60*60) }}<br>
                                        <p>{!! $new->content !!}</p>
                                    </td>
                                </tr>
                                @php $i++; @endphp
                                @if ($i == 3)
                                    @break
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @yield('body')
            </div>
        </td>
        <td class="menu">@include('includes.right_menu')</td>
    </tr>

    </tbody>
</table>

<script type="text/javascript" src="/js/error.js"></script>
@include('includes.footer')

</body>
</html>
