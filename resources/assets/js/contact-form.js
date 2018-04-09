var $form = $('#contact-form');
$('#contact-form').validate({
    rules: {
        name: {
            required: true,
            maxlength: 70,
            minlength: 2
        },
        email: {
            required: true,
            maxlength: 24,
            pattern: /^[\w\.\d-_!#$%&'*+/=?^`{|}~]+@[\w\.\d-_]+\.\w{2,4}$/i
        },
        phone: {
            required: true,
            maxlength: 11,
            number: true
        },
        message: {
            required: true,
            maxlength: 150
        }
    },
    messages: {
        name: {
            required: "Введите имя",
            maxlength: "Вы ввели больше 12 символов"
        },
        email: {
            required: "Введите email",
            maxlength: "Вы превысили количество символов",
            pattern: "Неверный формат"
        },
        phone: {
            required: "Введите номер телефона",
            maxlength: "Разрешено не более 11 символов",
            number: 'Неверный формат'
        },
        message: {
            required: "Введите сообщение",
            maxlength: "Количество символов превышено"
        }
    },
    focusInvalid:false,
    submitHandler: function() {
        'use strict';
            // remove the error class
            $('.form-group').removeClass('error');
            $('.help-block').remove();

            // get the form data
            var formData = {
                'name': $('input[name="name"]').val(),
                'email': $('input[name="email"]').val(),
                'phone': $('input[name="phone"]').val(),
                'message': $('textarea[name="message"]').val()
            };
            formData['phone']=phone(formData['phone'])
            // process the form
        $.ajax({
            type: 'POST',
            url: '/api/v1/feedback',
            data: formData,
            dataType: 'json',
            encode: true
        }).done(function (data) {
            console.log('done');
            $('.modal-message').hide();
            $('.btn-submit')
                .css({'background-color': "green"})
                .html("Отправлено <i class='fa fa-check'></i>").attr('disabled', 'disabled')
            $('input[name="name"]').val(''),
                $('input[name="email"]').val(''),
                $('input[name="phone"]').val(''),
                $('textarea[name="message"]').val('')
            setTimeout(function () {
                $('.modal').modal('hide');
                $('body').removeClass('modal-open');
                $('.btn-submit')
                    .css({'background-color': "#2A88C9"})
                    .html("Отправлено <i class='fa fa-send'></i>").removeAttr('disabled', 'disabled')
            },3000);


        }).fail(function (data) {
            /*$('.modal-message')
                .css({"background-color": "e40f0f",
                    "color": "white",
                    "padding": "20px 50px",
                    "border": "1px solid #e40f0f",
                    "margin-top": "20px",
                    "display": "block",
                    "font-size": "16px"})
                .text(error);*/
            // for debug
            $('.btn-submit')
                .css({'background-color': "#e40f0f"})
                .html("Ошибка <i class='fa fa-times'></i>");
            console.log(data);
            var error_msg = "";
            if(data.status === 0) {
                error_msg = "Отсутствует подключение к интернету"
            }
            else if(data.status === 500) {
                error_msg = "Ошибка сервера. Повторите попытку позднее"
            }
            else {
                error_msg = "Проверьте правильность введенных данных"
            }
            $('.modal-message')
                .css({"background-color": "e40f0f",
                    "color": "black",
                    "padding": "20px 50px",
                    "border": "1px solid #e40f0f",
                    "margin": "0 10px",
                    "display": "block",
                    "font-size": "16px"})
                .text(error_msg);



        });
    }});
function phone (tel){
    switch (tel[0]){
        case '+':
            tel=tel.replace(/\+7/, '');
            break;
        case '8':
            tel=tel.replace(/8/,'');
            break;

        case '7':
            tel=tel.replace(/7/,'');
            break;
    }
    return tel
}

