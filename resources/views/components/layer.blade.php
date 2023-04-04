<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <meta name="viewport" content="width=device-width initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="/css/Bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/app.css" />
{{--  Дополнительные стили для конкретной страницы --}}
    @if(isset($styles))
        @foreach($styles as $style)
            <link rel="stylesheet" href="{{$style}}" />
        @endforeach
    @endif
    <script type="text/javascript" src="/js/Bootstrap/bootstrap.bundle.js"></script>
    <script type="text/javascript" src="/js/vendor.js"></script>
    <script type="text/javascript" src="/js/scripts.js"></script>
    <title>{{$title ?? '<Заголовок>'}}</title>
</head>

<body>
    <div class="w-100 h-100 d-flex flex-column">
        <header class="header js-header flex-grow-0">
            <div class="header__wrapper">
                <div class="header__left">
                    <div class="header__burger js-nav-burger"><span class="header-icon header-icon_burger"></span></div>
                    <div class="header__search">
                        <a href="/">
                            <div class="logo row m-0">
                                <div class="col p-0 align-items-center">
                                    <img src="/images/svg/arm_logo.svg" alt="" style="width: 105px;">
                                </div>
                                <div class="col-auto ps-2 pe-1">
                                    <div class="logo__separator"></div>
                                </div>
                                <div class="col p-0">
                                    <div class="logo__text">КОНТАКТ-</div>
                                    <div class="logo__text">ЦЕНТР</div>
                                </div>
                            </div>
                        </a>
                        @if(false)
                            <form class="search-form" action="javascript:void(0)"><input class="search-form__input" /><button class="search-form__submit" type="submit"></button></form>
                        @endif
                    </div>
                    <div class="header__nav z-1">
                        <nav class="header-nav js-nav d-flex flex-column pt-5 pb-2">
                            <div class="header-nav__avatar-wrapper d-flex flex-column flex-grow-0">
                                <div class="header-nav__avatar">
                                    <img class="header-nav__avatar-img" src="https://picsum.photos/300" alt="Фотография пользователя" />
                                </div>
                                @if(isset($user))<div class="header-nav__avatar-title">{{$user->getName()}}</div>@endif
                            </div>
                            <div class="header-nav__wrapper js-nav-bar mCustomScrollbar _mCS_1 mCS_no_scrollbar pt-5 flex-grow-1">
                                <ul class="header-nav__list p-0">
                                    <li class="header-nav__item js-nav-item">
                                        <a class="header-nav__link js-nav-burger" href="javascript:void(0)"><span class="header-icon header-icon_settings"></span></a>
                                        <div class="header-nav__hidden-wrapper">
                                            <div class="header-nav__title js-nav-title"><a class="header-nav__sublink" href="/admin">Панель администратора</a></div>
                                        </div>
                                    </li>
                                    <li class="header-nav__item js-nav-item">
                                        <a class="header-nav__link js-nav-burger" href="javascript:void(0)"><span class="header-icon header-icon_table"></span></a>
                                        <div class="header-nav__hidden-wrapper">
                                            <div class="header-nav__title js-nav-title"><a class="header-nav__sublink" href="/supportChat/chat">Панель оператора</a></div>
                                        </div>
                                    </li>
                                    <li class="header-nav__item js-nav-item">
                                        <a class="header-nav__link js-nav-burger" href="javascript:void(0)"><span class="header-icon"></span></a>
                                        <div class="header-nav__hidden-wrapper ">
                                            {{--                                    <div class="pt-2 pb-2">--}}
                                            {{--                                        <div class="separator"></div>--}}
                                            {{--                                    </div>--}}

                                        </div>
                                        {{--                                <div class="header-nav__hidden-wrapper">
                                                                            <div class="header-nav__title js-nav-title">ааа</div>
                                        --}}{{--                                    <div class="separator">ааа</div>--}}{{--
                                                                        </div>--}}
                                    </li>
                                    <li class="header-nav__item js-nav-item">
                                        <a class="header-nav__link js-nav-burger" href="javascript:void(0)"><span class="header-icon header-icon_table"></span></a>
                                        <div class="header-nav__hidden-wrapper">
                                            <div class="header-nav__title js-nav-title">Другие ресурсы</div>
                                            <ul class="header-nav__sublist js-nav-list">
                                                <li class="header-nav__subitem"><a class="header-nav__sublink" href="/redirect/mis22">МИС22</a></li>
                                            </ul>
                                        </div>
                                    </li>
{{--                                    --}}

{{--                                    <li class="header-nav__item js-nav-item">--}}
{{--                                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-error_message">--}}
{{--                                            Запуск модального окна--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                    {{--                            <li class="header-nav__item js-nav-item"><a class="header-nav__link" href="javascript:void(0)"><span class="header-icon header-icon_help"></span></a>--}}
                                    {{--                                <div class="header-nav__hidden-wrapper"><a class="header-nav__title" href="#">Справка</a></div>--}}
                                    {{--                            </li>--}}
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
                <div class="header__right">
                    <div class="header__right-main">
                        <div class="header__right-actions">
                            {{--                    {{date('d.m.y H:i:s', session()->get('expiresAtARMToken')) }}--}}
                            {{-- Сообщения, пока их нет --}}
                            @if(false)
                                <a class="header__action" href="javascript:void(0)">
                                    <span class="header-icon header-icon_mail"></span>
                                </a>
                            @endif
                            {{-- Уведомления, пока их нет --}}
                            @if(false)
                                <a class="header__action header__action_notify" href="javascript:void(0)" data-notify="3">
                                    <span class="header-icon header-icon_bell"></span>
                                </a>
                            @endif
                        </div>
                        <div class="header__right-titles">
                            @if(isset($user))
                                <div class="header__right-title">{{$user->getName()}}</div>
                                <div class="header__right-subtitle">{{$user->getGroupName()}}</div>
                                <input id="user_id" class="invisible" value="{{$user->getUserId()}}">
                            @endif
                            {{--                    <div hidden>{{$user->getUserId()}}</div>--}}
                        </div>
                    </div>
                    <div class="header__right-side"><a class="header__logoout" href="/logout"><span class="header-icon header-icon_logoout"></span></a></div>
                </div>
            </div>
        </header>
        <div class="main flex-grow-1">
            <div class="main__wrapper section-wrapper">
                {{$slot}}
            </div>
        </div>
    </div>

    <!-- Модальное окно для ошибок -->
    <div class="modal fade" id="modal-error_message" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div id="modal-error_message__header" class="modal-header">
                    <h1 id="modal-error_message__title" class="modal-title fs-5">Заголовок модального окна</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div id="modal-error_message__body" class="modal-body">
                    ...
                </div>
            </div>
        </div>
    </div>
</body>
