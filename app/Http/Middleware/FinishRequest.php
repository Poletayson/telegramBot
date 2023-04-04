<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Посредник для быстрой отправки ответа до выполнения трудоёмкой задачи
 */
class FinishRequest
{
    protected static $next;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        self::$next = $next;
        return response(['code' => 200, 'data' => [], 'msg' => '']);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $response
     */
    public function terminate($request, $response)
    {
        call_user_func(self::$next, $request);
    }
}
