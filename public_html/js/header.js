$('button.signout').click(function(){
    window.location.assign('/signout');
});
$(document).mousedown(function(e){
    if (e.originalEvent.button == 2){
        e.preventDefault();
        return false;
    }
});
$(document.body).on('contextmenu', function(e){
    return false;
});
$('input.search').on('blur', function(e){
    setTimeout(function () {
        $('.search-offers').html('');
        $('.search-offers').addClass('d-none');
    }, 250);
});
let selected_offer = -1;
$('input.search').on('keydown', function(e){
    if (e.originalEvent.key == 'ArrowDown'){
        e.preventDefault();
        if (selected_offer < $('.search-offer').length - 1) {
            selected_offer++;
            select_offer();
        }
    }
    if (e.originalEvent.key == 'ArrowUp'){
        e.preventDefault();
        if (selected_offer > -1) {
            selected_offer--;
            select_offer();
        }
    }
});
function select_offer(){
    $('.search-offer').removeClass('offer-selected');
    if (selected_offer > -1 && selected_offer < $('.search-offer').length)
        $($('.search-offer')[selected_offer]).addClass('offer-selected');
}
function search(output = 'main', page = 'service'){
    if ($('input.search').val().trim() != '') {
        let data = new FormData();
        data.append('search', $('input.search').val().trim());
        $.ajax({
            url: '/api/search/' + page,
            method: 'post',
            data: data,
            processData: false,
            contentType: false,
            success: function (data, status, xhr) {
                if (status == 'success') {
                    if (output == 'main') {
                        history.pushState(null, '', '/'+page+'/search');
                        $('.body').html('<h3>Результаты поиска:</h3>');
                        $('.body').append('<table class="table search_results"><tbody></tbody></table>');
                        for (let i = 0; i < data.data.length; i++) {
                            let label = '';
                            if (data.data[i].trashed)
                                label = ' - <span style="color: rgba(225, 25, 25, 0.75)">услуга больше не оказывается</span>';
                            let name, content;
                            if (data.data[i].name != null) name = data.data[i].name;
                            if (data.data[i].title != null) name = data.data[i].title;
                            if (data.data[i].description != null) content = data.data[i].description;
                            if (data.data[i].content != null) content = data.data[i].content;
                            $('.search_results').append('<tr><td><p><a target="_blank" href="/' + page + '/' + data.data[i].id + '"><b>' + name + '</b></a>' + label + '</p><p>' + content + '</p></td></tr>');
                        }
                    }
                    if (output == 'offer'){
                        if (data.data.length > 0) $('.search-offers').removeClass('d-none');
                        $('.search-offers').html('');
                        for (let i = 0; i < data.data.length; i++){
                            let name;
                            if (data.data[i].name != null) name = data.data[i].name;
                            if (data.data[i].title != null) name = data.data[i].title;
                            $('.search-offers').append('<div class="search-offer">'+name+'</div>');
                        }
                        reset_offers_handler();
                        select_offer();
                    }
                }
            },
            error: function (xhr) {
                display_error(xhr);

            }
        });
    } else {
        $('.search-offers').html('');
        $('.search-offers').addClass('d-none');
    }
}
function reset_offers_handler(){
    $('.search-offer').unbind('click');
    $('.search-offer').click(function(e){
        $('input.search').val($(this).text());
        $('input.search').focus();
        selected_offer = -1;
        select_offer();
    });
}
