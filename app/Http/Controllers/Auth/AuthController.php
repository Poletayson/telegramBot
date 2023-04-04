<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Контроллер аутентификации
 */
class AuthController extends Controller
{
    /**
     * Показать страницу входа
     * @return Application|Factory|View
     */
    public function showLogin (): View|Factory|Application
    {
        return view('login');
    }

    /**
     * Показать страницу с требованием ввода паспорта
     * @return Factory|View|Application
     */
    public function showPassportConfirmation (): Factory|View|Application
    {
        return view('passportConfirmation');
    }

    /**
     * Удостоверить личность по паспортным данным
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function PassportConfirmation (Request $request) {
        $array = $request->all();   //Получаем ввод и файлы из запроса

        if (strlen($array['series']) != 4 && strlen($array['number']) != 6) {
            return redirect()->back()      //Возвращаемся на предыдущую
            ->withInput($request->only(['series', 'number']))
                ->withErrors(['message'=>'Данные заполнены некорректно']);
        }
        //Определяем параметры
        $series = $array['series'];
        $number = $array['number'];

        $user = UserController::getAuthentifiedUser();

        //TODO Здесь должно быть обращение к какому-то API
        $isPassportConfirmed = true;

        $user->setIsPassportConfirmed($isPassportConfirmed);

        if ($user->isPassportConfirmed()){
            UserController::putUserToSession($user);    //Запоминаем пользователя в сессии
            return redirect()->intended();  //Переходим на страницу, на которую хотели
        } else {
            return redirect()->back()      //Возвращаемся на предыдущую
            ->withInput($request->only(['series', 'number']))
                ->withErrors(['message'=>'Не удалось проверить паспортные данные']);
        }


    }

    /**
     * Попытаться войти
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function login (Request $request) {
        $array = $request->all();   //Получаем ввод и файлы из запроса
        $remember = $request->has('remember');  //'Запомнить меня'

        //Определяем параметры
        $credentials = [];
        if (isset($array['login']) && $array['password']) {
            //Логин и пароль из запроса
            $credentials = ['login' => $array['login'],
                'password' => $array['password']
            ];
        } elseif ($request->hasHeader('oneTimeToken')) {
            //Заголовок с oneTimeToken
            $credentials = ['oneTimeToken' => $request->header('oneTimeToken')];
        }

        try {
            //Попытка аутентифицироаться
            if (Auth::attempt($credentials, $remember)) {
                return redirect()->intended();  //Переходим на страницу, на которую хотели
            } else {
                return redirect()->back()      //Возвращаемся на предыдущую
                ->withInput($request->only(['login', 'remember']))
                    ->withErrors(['message'=>'Не удалось аутентифицироваться']);
            }
        } catch (\Exception $exception) {
            return redirect()->back()      //Возвращаемся на предыдущую
            ->withInput($request->only(['login', 'remember']))
                ->withErrors($exception->getMessage());
        }



    }

    /**
     * Выйти
     * @param Request $request
     * @return void
     */
    public function logout (Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
