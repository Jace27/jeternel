@include('includes.preload')
<!doctype html>
<html lang="ru">
<head>
    @include('includes.head')
    <title>@yield('title')</title>
</head>
<body>

<table>
    <tbody>

    @include('includes.header')
    <tr>
        <td class="menu">@include('includes.left_menu')</td>
        <td class="align-top">
            @include('includes.message')

            <div class="body">
                @yield('body')
            </div>
        </td>
        <td class="menu">@include('includes.right_menu')</td>
    </tr>

    </tbody>
</table>

@include('includes.footer')

</body>
</html>
