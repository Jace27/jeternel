$('.menu li, .articles_list li').click(function(e){
    e.stopPropagation();
    if ($(this).children('ul').length == 0){
        $(this).children('a')[0].click();
    } else if (e.target.nodeName != 'A') {
        $(this).children('ul').slideToggle(150);
        $(this).children('hr').slideToggle(150);
    }
    if ($(this).children('a').hasClass('btn-cat-new')){
        if ($(this).parent().parent().hasClass('left_menu'))
            $('#cat-new-type-select').val(1);
        if ($(this).parent().parent().hasClass('right_menu'))
            $('#cat-new-type-select').val(2);
        change_cat_set();
        $('#CatNewModal').modal('show');
    }
});
