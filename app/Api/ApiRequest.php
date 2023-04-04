<?php

namespace App\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

/**
 * Запрос к API
 */
class ApiRequest
{
    protected static $user = null;
    protected static $accessToken = null;
    protected static $refreshToken = null;
    /**
     * Время истечения срока дейстия токена
     */
    protected static $expiresAt = null;

    public function __construct()
    {
        if (session()->has('accessARMToken'))
            static::$accessToken = session('accessARMToken');
        if (session()->has('refreshARMToken'))
            static::$refreshToken = session('refreshARMToken');
        if (session()->has('expiresAtARMToken'))
            static::$expiresAt = session('expiresAtARMToken');
    }

    /**
     * Запрос метода API. Возвращает ассоциативный массив
     * @param string $url Адрес сервиса
     * @param string $method Метод API
     * @param string $requestMethod HTTP-метод
     * @param array|null $params Параметры запроса
     * @param string|null $api_url Базовый URL API
     * @return mixed
     */
    protected static function callApi($url, $method, string $requestMethod = 'post', array $params = null, string $api_url = null){
        if(!static::$accessToken) {
            throw new Exception(json_encode(['method' => $method, 'message' => 'AccessToken isn`t set']));
        }

        if(null === $api_url) {
            $api_url = env('API_URL');
        }

        switch (strtolower($requestMethod)) {
            case 'get': {
                $response = Http::withToken(env('API_KEY'))->get("{$api_url}/{$url}", $params);
                break;
            }
            case 'post': {
                $params = array_merge($params, ['jsonrpc' => '2.0', 'id' => '1', 'token' => static::$accessToken, 'method' => $method]);
                $response = Http::withToken(env('API_KEY'))->post("{$api_url}/{$url}", $params);
                break;
            }
            default: {
                $response = null;
            }
        };
        return $response?->json();
    }

    /**
     * Запрос метода API. Возвращает ассоциативный массив. Устаревший метод
     * @param $url
     * @param $method
     * @param $params
     * @param $base_url
     * @return mixed
     */
    public static function callApiOld($url = null, $method = null, $params = null, $base_url = null){
        if(!static::$accessToken) {
            throw new Exception(json_encode(['method' => $method, 'message' => 'AccessToken isn`t set']));
        }
        $params['token'] = static::$accessToken;

        $ch = curl_init();
        $postFields = [
            'jsonrpc' => '2.0',
            'id'      => '1',
            'method'  => $method,
            'params'  => $params,
        ];
        if(null === $base_url) {
            $base_url = config('api.API_URL');
        }
        $options = [
            CURLOPT_URL            => $base_url."/".$url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => json_encode($postFields, JSON_UNESCAPED_UNICODE),
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ];


        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);

        curl_close($ch);

        return json_decode($result, true);
    }

    /**
     * Вызов API и получение результата
     * @param string $url Адрес сервиса
     * @param string $method Метод API
     * @param string $requestMethod  Метод
     * @param array $params Массив параметров
     * @param null $resultKey Ключ значения, которое нужно вернуть
     * @return mixed|null
     * @throws \Exception
     */
    protected static function callApiMethod($url, $method, $requestMethod = 'post', $params = null, $resultKey = null) {

        $answer = static::callApi($url, $method, $requestMethod, $params);
        if(!isset($answer['result'])) {
            throw new \Exception(json_encode(['message' => json_encode($answer)]));
        }
        $answer = $answer['result'];

        if($resultKey) {
            return $answer[$resultKey] ?? null;
        }

        return $answer;
    }
}
