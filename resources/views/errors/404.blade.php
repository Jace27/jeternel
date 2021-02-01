@include('includes.preload')
    <!doctype html>
<html lang="ru">
<head>
    @include('includes.head')
    <title>Страница не найдена</title>
    <style>
        html, body {
            margin: auto;
            text-align: center;
        }
        img {
            max-width: 100%;
        }
        h3 {
            color: rgb(207, 97, 45);
        }
    </style>
</head>
<body>

<div class="container">

    <div class="align-middle">
        <img src="/images/logo.png"><br>
        <h3>Извините, ничего не найдено</h3>
        <a href="/">Вернуться на главную страницу</a>
    </div>

</div>

@include('includes.footer')

</body>
</html>
