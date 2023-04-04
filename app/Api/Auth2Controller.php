<?php

namespace App\Api;

use App\Models\Auth;
use App\Api;
use App\Models\Auth\User;

/**
 * Класс, реализующий методы взаимодейстия с API аутентификации
 */
class Auth2Controller extends ApiRequest
{
    /**
     * Войти по логину. Данные записыватся в сессию
     * @param $login
     * @param $password
     * @return $this
     * @throws \Exception
     */
    public function login ($login, $password) {
        $params = [
            'login' => $login,
            'password' => $password,
        ];

        $this->loginByCredentials($params);

        return $this;
    }

    /**
     * Войти по ID пользователя
     * @param $userId
     * @param $password
     * @return $this
     * @throws \Exception
     */
    public function loginById ($userId, $password) {
        $params = [
            'userId' => $userId,
            'password' => $password,
        ];
        $this->loginByCredentials($params);

        return $this;
    }

    /**
     * Войти по предосталенным учётным данным
     * @param $credentials
     * @return $this
     * @throws \Exception
     */
    private function loginByCredentials($credentials) {
        $credentials['app'] = env('API_APP_NAME');
        $answer = static::callApi('auth', 'Auth2.Login', params: $credentials);
        //Результат не получен
        if(!isset($answer['result'])) {
            //Была возвращена ошибка
            if (isset($answer['error'])) {
                $answer['error']['method'] = 'loginByCredentials';
//                $answer['error']['message'] .= var_export($credentials, true);
                throw new \Exception(json_encode($answer['error']));
            }
            else
                throw new \Exception(json_encode(['method' => 'loginByCredentials', 'message' => 'Не удалось осуществить вход']));
        }

        static::$accessToken = $answer['result']['accessToken'];    //токен доступа
        static::$refreshToken = $answer['result']['refreshToken'];    //токен обноления
        static::$expiresAt = $answer['result']['expiresAt'];    //время, когда истекает время жизни токена

        session(['accessARMToken' => static::$accessToken,
            'refreshARMToken' => static::$refreshToken,
            'expiresAtARMToken' => static::$expiresAt]);

        return $this;
    }


    /**
     * Войти при помощи oneTimeToken
     * @return $this
     * @throws \Exception
     */
    public function loginByToken($oneTimeToken) {
        $credentials['app'] = env('API_APP_NAME');
        $credentials['ott'] = $oneTimeToken;

        $answer = static::callApiOld('auth', 'Auth2.LoginByToken', $credentials);
        //Результат не получен
        if(!isset($answer['result'])) {
            //Была возвращена ошибка
            if (isset($answer['error'])) {
                $answer['error']['method'] = 'loginByToken';
//                $answer['error']['message'] .= var_export($credentials, true);
                throw new \Exception(json_encode($answer['error']));
            }
            else
                throw new \Exception(json_encode(['method' => 'loginByToken', 'message' => 'Не удалось осуществить вход по токену']));
        }

        $this->accessToken = $answer['result']['accessToken'];    //токен доступа
        $this->refreshToken = $answer['result']['refreshToken'];    //токен обноления
        $this->expiresAt = $answer['result']['expiresAt'];    //время, когда истекает время жизни токена

        session(['accessARMToken' => static::$accessToken,
            'refreshARMToken' => static::$refreshToken,
            'expiresAtARMToken' => static::$expiresAt]);

        return $this;
    }

    /**
     * Получить доступ от имени пользователя с указанными учётными данными
     * @param $credentials
     * @return Auth\User|void Аутентифиированный пользоатель
     * @throws \Exception
     */
    public function retrieveByCredentials($credentials) {
        //Сначала логинимся
        if (isset($credentials['password'])) {
            if (isset($credentials['login']))
                $this->login($credentials['login'], $credentials['password']);
            elseif (isset($credentials['userId']))
                $this->loginById($credentials['userId'], $credentials['password']);
        } elseif (isset($credentials['oneTimeToken'])) {
            $this->loginByToken($credentials['oneTimeToken']);
        }

        return $this->requestUser()->setLogin($credentials['login'])->setPassword($credentials['password']);
    }


    /**
     * Получить oneTimeToken
     * @throws \Exception
     */
    public function getOneTimeToken () {
        $oneTimeToken = $this->callApiMethod('auth', 'Auth2.GetOneTimeToken')['ott'];

        return $oneTimeToken;
    }

    /**
     * Проверить доступ к API в данный момент. Может выполняться попытка обноления токенов
     * @return boolean
     * @throws \Exception
     */
    public function checkOrRefreshAccess (): bool
    {
        $accessValid = $this->accessToken && $this->checkAccessToken(); //Проверяем токен, если он есть
        //Токен есть и он невалиден
        if ($this->accessToken && !$accessValid) {
            $this->refreshTokens ();   //Обноляем токены
            $accessValid = $this->checkAccessToken (); //Проверяем снова
        }
        return $accessValid;
    }


    /**
     * Проверить валидность токена доступа
     * @return boolean
     * @throws \Exception
     */
    public function checkAccessToken (): bool
    {
        try {
            $accessTokenValid = $this->callApiMethod('auth', 'Auth2.CheckAccessToken')['valid'] == 'true';
        } catch (\Exception $exception) {
            $accessTokenValid = false;
        }

        return $accessTokenValid;
    }

    /**
     * Обновление токенов пользователя
     * @return $this
     * @throws \Exception
     */
    public function refreshTokens (): Auth2Controller
    {
        if(!$this->refreshToken) {
            throw new \Exception(json_encode(['method' => 'refreshTokens', 'message' => 'RefreshToken isn`t set']));
        }
        $params = ['refreshToken' => $this->refreshToken];

        $result = $this->callApiMethod('auth', 'Auth2.Refresh', $params);

        if(!$result['accessToken'] || !$result['refreshToken']) {
            throw new \Exception(json_encode(['method' => 'refreshTokens', 'message' => 'Refreshing failed']));
        }

        static::$accessToken = $result['accessToken'];    //токен доступа
        static::$refreshToken = $result['refreshToken'];    //токен обноления
        static::$expiresAt = $result['expiresAt'];    //время, когда истекает время жизни токена


        session(['accessARMToken' => static::$accessToken,
            'refreshARMToken' => static::$refreshToken,
            'expiresAtARMToken' => static::$expiresAt]);
        return $this;
    }

    public function logout ($allDevices = false)
    {
        if(!static::$refreshToken) {
            throw new \Exception(json_encode(['method' => 'logout', 'message' => 'RefreshToken isn`t set']));
        }
        $params = ['refreshToken' => $this->refreshToken];
        if ($allDevices)
            $params['allDevices'] = $allDevices;

        $this->callApiMethod('auth', 'Auth2.Logout', $params);

        return $this;
    }

    /**
     * Запросить пользователя со всеми данными по API
     * @return User|null
     * @throws \Exception
     */
    public function requestUser(): ?Auth\User
    {
        $userData = $this->getUserInfo();

        $user = new Auth\User($userData['id']);
        $user->setName($userData['name']);
        $user->setGroupId($userData['groupId']);
        $user->setGroupName($userData['groupName']);
        $user->setClinicId($userData['clinicId']);
        $user->setPost($userData['post']);

        $userResources = new Auth\UserResources();
        $userResources->clinics = $userData['allowed']['clinics'];
        $userResources->resources = $userData['allowed']['resources'];
        $userResources->workers = $userData['allowed']['workers'];
        $userResources->hospitals = $userData['allowed']['hospitals'];
        $userResources->educInst = $userData['allowed']['educInst'];
        $user->setUserResources($userResources);
        return $user;
    }

    /**
     * Полуить информацию о залогиненном пользователе
     * @return mixed|null Массив с данными: id, name, groupId, groupName, clinicId, post, allowed[]
     * @throws \Exception
     */
    public function getUserInfo () {
        return $this->callApiMethod('auth', 'Users.GetUserInfo');
    }

    /**
     * Получить полезную нагрузку JWT-токена
     * @param $token
     * @return mixed
     */
    private function getJWTPayload($token) {
        return json_decode(base64_decode(explode('.', $token)[1]), true);
    }
}
