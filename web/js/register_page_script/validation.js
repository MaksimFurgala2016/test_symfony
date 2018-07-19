/**
 * Created by furga on 03.07.2018.
 */
$(document).ready(function () {
    $('#form-register').validate(
        {
            rules: {
                "form[login]": {required: true,
                        rangelength: [4,15],
                        regex: /^[A-Za-z0-9]+$/
                },
                "form[password]": {required: true,
                            rangelength: [5,25],
                            regex: /^(?=.*\d)(?=.*\w)(?!.*\s).*$/

                },
                "form[file]": {required: false
                },
                "form[gender]": {required: false}
            },
            messages: {
                "form[login]": {required: 'Введите логин!',
                            rangelength: 'Логин должен содержать от 4 до 15 букв или цыфр!',
                            regex: 'Логин должен состоять из букв латинского алфавита и,возможно, цифр!'},

                "form[password]": {required: 'Введите пароль!',
                            rangelength: 'Пароль должен содержать от 5 до 25 символов!',
                            regex: 'Пароль должен содержать как минимум одну цыфру!'}
            }
        }
    );
});