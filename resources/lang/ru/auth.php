<?php

return [
    'failed' => 'These credentials do not match our records.',
    //'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',


    'signing_in' => 'Вход на сайт',
    'sign_in' => 'Войти',
    'email' => 'E-Mail',
    'password' => 'Пароль',
    'remember_on_this_device_label' => 'Запомнить на этом устройстве',
    'forgot_password' => 'Забыли пароль?',

    'registration' => 'Регистрация',
    'registration_helper_text' => 'Если у вас ещё нет учетной записи, зарегистрируйтесь. '
        . 'Это займёт несколько минут.<br>После регистрации вы получите доступ к онлайн-сервису Aspiot CRM и Учёт.',

    'validation' => [
        'email' => [
            'unique' => 'В системе уже существует пользователь с таким E-Mail',
        ],
        'phone' => [
            'unique' => 'В системе уже существует пользователь с таким номером телефона',
        ],
        'password' => [
            'length' => 'Длина пароля должна составлять не менее 8 символов',
            'user' => 'В системе нет зарегистрированного пользователя с таким E-Mail',
        ],
    ],
];
