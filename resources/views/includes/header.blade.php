<tr>
    <td style="text-align: center"><a href="/"><img src="/images/logo.png"></a></td>
    <td>
        <div class="top-menu">
            <ul>
                <li><a href="/">Главная</a></li>
                <li><a href="/articles/">Стандарты работы</a></li>
                <li><a href="/news/">Новости</a></li>
                <li><a href="/disport/">Календарь диспорта</a></li>
                <li><a href="/promotions/">Акции</a></li>
                @if(\App\Functions::is_admin())
                    <li><a href="/admin/">Панель управления</a></li>
                @endif
            </ul>
        </div>
        <div class="search-input">
            <input class="form-control search" type="text" placeholder="Введите запрос"><img src="/images/icons/search.svg" class="bi bi-search" width="24" height="24">
            <div class="search-offers d-none"></div>
        </div>
    </td>
    <td><button class="btn btn-outline-primary signout"><img src="/images/icons/box-arrow-right.svg" class="bi bi-search" width="24" height="24"></button></td>
</tr>
