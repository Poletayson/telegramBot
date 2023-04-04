<?php

namespace App\Providers;

use App\Api\Auth2Controller;
use App\Api\RefsController;
use App\Http\Controllers\Auth\UserController;
use App\Models\Auth;
use http\Env\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;


/**
 * Проайдер пользователей, осноанный на API Auth2 МИЦ22
 */
class Auth2UserProvider implements UserProvider
{
    /**
     * @var Auth2Controller
     */
    private Auth2Controller $auth2Controller;

    /**
     * Create a new Auth2 user provider.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @return void
     */
    public function __construct()
    {
//        $this->model = $userModel;
        $this->auth2Controller = new Auth2Controller(); //Создаём контроллер для работы с API
    }

    /**
     * Retrieve a user by the given credentials/Получить доступ от имени пользователя с указанными учётными данными.
     * Пользователь сериализуется и запоминается в сессии
     *
     * @param array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws \Exception
     */
    public function retrieveByCredentials(array $credentials)
    {
        $user = null;

        //Если ни один параметр не задан
        if (empty($credentials))
            return;

        $user = $this->auth2Controller->retrieveByCredentials($credentials);


        if ($user) {
            $MO = (new RefsController())->getClinicById($user->getClinicId());  //Получаем МО пользователя
            $user->setMedicineOrganization($MO);
            //Запоминаем сериализованного пользователя в сессии
            UserController::putUserToSession($user);
        }

        return $user;

    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials  Request credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, Array $credentials)
    {
        $isValid = true;
        if (isset($credentials['userId']))
            $isValid = ($user->getAuthIdentifier() == $credentials['userId']);
        if (isset($credentials['password']))
            $isValid = $isValid && ($user->getAuthPassword() == $credentials['password']);
        return $isValid;
    }

    /**
     * Получить пользователя по идентификатору. Работает не совсем так, как предполагается:
     * возвращается аутентифицированный пользователь, ID не используется
     * @throws \Exception
     */
    public function retrieveById($identifier) {
        //Проверяем валидность токена доступа и обновляем при необходимости. Если доступа нет, возвращаем null
        if (! $this->auth2Controller->checkOrRefreshAccess ())
            return null;
        try {
            $user = UserController::getAuthentifiedUser();


//            $userInfo = $this->auth2Controller->getUserInfo();
//            $user = new Auth\User($userInfo['id']);
//            $user->setName($userInfo['name']);
//            $user->setGroupId($userInfo['groupId']);
//            $user->setGroupName($userInfo['groupName']);
//            $user->setClinicId($userInfo['clinicId']);
//            $user->setPost($userInfo['post']);
//            $user->setUserResources(new Auth\UserResources());
//
//            $MO = (new RefsController())->getClinicById($user->getClinicId());  //Получаем МО пользователя
//            $user->setMedicineOrganization($MO);

        } catch (\Exception $ex) {
            return null;
        }

        return $user;
    }

    public function retrieveByToken($identifier, $token) {

    }

    public function updateRememberToken(Authenticatable $user, $token) {

    }
}
