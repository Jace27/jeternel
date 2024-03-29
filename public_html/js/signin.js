let submitting = false;
$('form[name=signin_form]').on('submit', function(e){
    if (!submitting) e.preventDefault();
});
$('input[name=phone]').change(function(e){
    last_errors = [];
    check_user(e.target.value);
});
let last_errors = [];
let validator = new FormValidator('signin_form', [
    {
        name: 'phone',
        display: 'Номер телефона',
        rules: 'required|min_length[10]|numeric|callback_user'
    },
    {
        name: 'password',
        display: 'Пароль',
        rules: 'required|min_length[8]'
    },
], function(errors, event){
    last_errors = errors;
    if (last_errors.length != 0) submitting = false;
    display_errors();
});
validator.registerCallback('user', function(value){
    check_user(value);
    return true;
});
validator.setMessage('required', 'Поле %s должно быть заполнено');
validator.setMessage('min_length', 'Поле %s должно содержать не меньше %s символов');
validator.setMessage('numeric', 'Поле %s должно содержать только цифры');

function display_errors(){
    if (last_errors.length > 0) {
        let div = document.createElement('DIV');
        for (let i = 0; i < last_errors.length; i++) {
            let p = document.createElement('P');
            p.innerText = last_errors[i].message;
            div.append(p);
        }
        $('#errors').html(div);
        $('#errors').removeClass('d-none');
    } else {
        $('#errors').html('');
        $('#errors').addClass('d-none');
    }
    align_middle();
}
function display_warning(warning){
    if (warning == '') {
        $('#warning').html('');
        $('#warning').addClass('d-none');
    } else {
        $('#warning').html('<b>' + warning + '</b>');
        $('#warning').removeClass('d-none');
    }
    align_middle();
}
function check_user(phone) {
    if (submitting) return;
    console.log('checking');
    let data = new FormData();
    data.append('phone', phone);
    $.ajax({
        url: '/api/check_phone',
        method: 'post',
        data: data,
        processData: false,
        contentType: false,
        success: function(data, status, xhr){
            if (status == 'success') {
                switch (data.status) {
                    case 'user does not exist':
                        last_errors.push({ message: 'Пользователя не существует' });
                        submitting = false;
                        display_errors();
                        break;
                    case 'new user':
                        display_warning('Вы входите в систему первый раз, на аккаунт будет установлен тот пароль, который вы введете сейчас');
                        break;
                    case 'user exist':
                        display_warning('');
                        break;
                }
                display_errors();
            } else {
                display_error(xhr);

                last_errors.push({ message: 'Не удалось проверить статус пользователя' });
                submitting = false;
                display_errors();
            }

            if (last_errors.length == 0){
                submitting = true;
                $('form[name=signin_form]').submit();
            }
        },
        error: function(xhr){
            display_error(xhr);

        }
    });
}



$(document).ready(function(){
    align_middle();
});
$('form').submit(function(e){
    align_middle();
});
function align_middle(){
    $('.align-middle').css('margin-top', Math.round((document.documentElement.clientHeight - $('.align-middle')[0].clientHeight) / 2.0 - 50) + 'px' );
}
