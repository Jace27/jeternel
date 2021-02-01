$('button.signout').click(function(){
    window.location.assign('/signout');
});
$('.search-input').click(function(){
    search();
});
$('input.search').on('keypress', function(e){
    if (e.originalEvent.key == 'Enter'){
        search();
    }
});
function search(){
    if ($('input.search').val().trim() != ''){
        let debug = false;
        if (debug) {
            let form = document.createElement('form');
            form.method = 'post';
            form.action = '/api/search/service';
            let input = document.createElement('input');
            input.type = 'text';
            input.name = 'search';
            input.value = $('input.search').val().trim();
            form.append(input);
            document.body.append(form);
            form.submit();
        } else {
            let data = new FormData();
            data.append('search', $('input.search').val().trim());
            $.ajax({
                url: '/api/search/service',
                method: 'post',
                data: data,
                processData: false,
                contentType: false,
                success: function (data, status, xhr) {
                    console.log([data, status, xhr]);
                    if (status == 'success') {
                        $('.body').html('<h3>Результаты поиска:</h3>');
                        for (let i = 0; i < data.length; i++) {
                            let label = '';
                            if (data[i].trashed) label = ' - <span style="color: rgba(225, 25, 25, 0.75)">услуга больше не оказывается</span>';
                            $('.body').append('<p><a target="_blank" href="/service/'+data[i].id+'"><b>'+data[i].name+'</b></a>'+label+'<br>'+data[i].description+'</p><hr>');
                        }
                    }
                }
            });
        }
    }
}
