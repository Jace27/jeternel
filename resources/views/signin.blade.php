@include('includes.preload')
<!doctype html>
<html lang="ru">
<head>
    @include('includes.head')
    <title>Вход в систему</title>
    <style>
        html, body {
            margin: auto;
            text-align: center;
        }
        input {
            margin: 5px;
        }
        img {
            max-width: 100%;
        }
        #messages > * {
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="align-middle">
        <img src="/images/logo.png">
        <div id="messages">
            <div class="alert alert-warning d-none" role="alert" id="warning"></div>
            @include('includes.message')
            <div class="alert alert-danger d-none" role="alert" id="errors"></div>
        </div>
        <form action="/signin" method="post" name="signin_form">
            @csrf
            <input type="text" name="phone" placeholder="Номер телефона"><br>
            <input type="password" name="password" placeholder="Пароль"><br>
            <input type="submit" value="Войти в систему" class="btn btn-outline-primary">
        </form>
    </div>

</div>

@include('includes.footer')

<script type="text/javascript" src="/js/validate.js"></script>
<script type="text/javascript" src="/js/signin.js"></script>

</body>
</html>
