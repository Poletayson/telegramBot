<?php

namespace App\Exceptions;

use Illuminate\Container\Container;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Session;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Exception|Throwable $exception)
    {
        //Для некоторых кодов HTTP-ответов установим кастомные шаблоны ответов
        if ($this->isHttpException($exception)) {
            /** @var HttpExceptionInterface $exception */

//            (new StartSession(new SessionManager(Container::getInstance())))->handle(request(), function (Response $response, Session $session){});
        //            Session::start();
        //            session()->start();
            $session = session();
            $allData = $session->all();


            switch ($exception->getStatusCode()){
                case 403: {
                    return response()->view('errors.403', ['exception' => $exception, 'styles' => ['/css/exception.css']], 403);
                    break;
                }
                case 404: {
                    return response()->view('errors.404', ['exception' => $exception, 'styles' => ['/css/exception.css']], 404);
                    break;
                }
                case 500: {
                    return response()->view('errors.500', ['exception' => $exception, 'styles' => ['/css/exception.css']], 500);
                    break;
                }
            }
        } elseif ($exception instanceof QueryException) {
            //Исключение при выполнении запроса к БД
            //Если не отладочный режим - показываем сообщение об ошибке. Передаём PDOException, чтобы не выводился запрос
            if (! config('app.debug')) {
                return response()->view('errors.500', ['exception' => $exception->getPrevious()], 500);
            }
        }

        return parent::render($request, $exception);
    }
}
