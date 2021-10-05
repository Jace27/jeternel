$('.menu li, .articles_list li, .articles_list td').click(function(e){
    e.stopPropagation();
    if ($(this).children('ul').length == 0 && $(this).children('table').length == 0){
        if ($(this).children('div').children('a').length != 0 && e.target.nodeName != 'BUTTON')
            $(this).children('div').children('a')[0].click();
        if ($(this).children('div').children('span').length != 0 && e.target.nodeName != 'BUTTON')
            $(this).children('div').children('span').children('b').children('a')[0].click();
    } else if (e.target.nodeName != 'A') {
        $(this).children('ul').slideToggle(150);
        $(this).children('table').slideToggle(150);
    }
    if ($(this).children('div').children('a').hasClass('btn-cat-new')){
        if ($(this).parent().parent().hasClass('left_menu'))
            $('#cat-new-type-select').val(1);
        if ($(this).parent().parent().hasClass('right_menu'))
            $('#cat-new-type-select').val(2);
        change_cat_set();
        $('#CatNewModal').modal('show');
    }
});
