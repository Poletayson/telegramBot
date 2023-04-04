<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <meta name="viewport" content="width=device-width initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="css/app.css"/>
    <link rel="stylesheet" href="css/Bootstrap/bootstrap-grid.min.css"/>
    <link rel="stylesheet" href="css/auth.css"/>
    <title>Вход в систему</title>
</head>

<body>
    <div class="auth__background d-flex align-items-center">
        <div class="auth__wrapper flex-grow-1">
            <div class="auth__container">
                <div class="auth__title">Вход в&nbsp;систему</div>
                @if($errors->any())
                    <div class="auth__status">{{isset (json_decode($errors->first(), true)['message']) ? json_decode($errors->first(), true)['message'] : $errors->first()}}</div>
                @endif
                <form class="auth__form" method="post" action="#">
                    @csrf
                    <div class="auth__form-input-wrapper">
                        <div class="auth__form-input-title">Логин</div><input class="input input_text" type="text" required="required" name="login"/>
                    </div>
                    <div class="auth__form-input-wrapper">
                        <div class="auth__form-input-title">Пароль</div><input class="input input_text" type="password" required="required" name="password"/>
                    </div>
                    <div class="auth__form-actions"><button class="auth__form-action button button_violet" type="submit"><span class="button__text">ВОЙТИ</span></button></div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/vendor.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>
