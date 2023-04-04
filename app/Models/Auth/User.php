<?php

namespace App\Models\Auth;

use App\Models\Human;
use App\Models\MedicineOrganization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Human implements Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    private $remember_token = null;
    private int|null $user_id = null;
    private string|null $login = null;



    private string|null $password = null;
    private int|null $groupId = null;
    private string|null $groupName = null;

    private MedicineOrganization|null $medicineOrganization = null;

    /**
     * @var bool Подтверждён ли паспорт. Изначально нет
     */
    private bool $isPassportConfirmed = false;

//    /**
//     * @var Rule[] Список правил, которые назначены пользователю
//     */
//    private array $rules = [];

    protected string $rememberTokenName = 'remember_token';

    public function __construct($user_id, $login = null, $password = null)
    {
        $this->user_id = $user_id;
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * Код клиники, в которой работает пользователь. Заполняется для мед. персонала
     * @var int|null
     */
    private int|null $clinicId = null;

    /**
     * Должность пользователя
     * @var string|null
     */
    private string|null $post = null;

    /**
     * @return string|null
     */
    public function getPost(): ?string
    {
        return $this->post;
    }

    /**
     * @param string|null $post
     * @return User
     */
    public function setPost(?string $post): User
    {
        $this->post = $post;
        return $this;
    }

    /**
     * @var UserResources
     */
    private UserResources $userResources;

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param null $user_id
     * @return User
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return null
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @param null $login
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return User
     */
    public function setPassword(?string $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getGroupId(): ?int
    {
        return $this->groupId;
    }

    /**
     * @param int|null $groupId
     * @return User
     */
    public function setGroupId(?int $groupId)
    {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    /**
     * @param string|null $groupName
     * @return User
     */
    public function setGroupName(?string $groupName): User
    {
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getClinicId(): ?int
    {
        return $this->clinicId;
    }

    /**
     * @param int|null $clinicId
     * @return User
     */
    public function setClinicId(?int $clinicId): User
    {
        $this->clinicId = (int)$clinicId;
        return $this;
    }

    /**
     * @return UserResources
     */
    public function getUserResources(): UserResources
    {
        return $this->userResources;
    }

    /**
     * @param UserResources $userResources
     * @return User
     */
    public function setUserResources(UserResources $userResources): User
    {
        $this->userResources = $userResources;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::getAuthIdentifierName()
     */
    public function getAuthIdentifierName(): string
    {
        return "user_id";
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::getAuthIdentifier()
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::getAuthPassword()
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::getRememberToken()
     */
    public function getRememberToken()
    {
        if (! empty($this->getRememberTokenName())) {
            return $this->{$this->getRememberTokenName()};
        }
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::setRememberToken()
     */
    public function setRememberToken($value)
    {
        if (! empty($this->getRememberTokenName())) {
            $this->{$this->getRememberTokenName()} = $value;
        }
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::getRememberTokenName()
     */
    public function getRememberTokenName(): string
    {
        return $this->rememberTokenName;
    }

    /**
     * @return MedicineOrganization|null
     */
    public function getMedicineOrganization(): ?MedicineOrganization
    {
        return $this->medicineOrganization;
    }

    /**
     * @param MedicineOrganization|null $medicineOrganization
     */
    public function setMedicineOrganization(?MedicineOrganization $medicineOrganization): void
    {
        $this->medicineOrganization = $medicineOrganization;
    }

//Правила

    /**
     * Проверить имеется ли правило у пользователя
     * @param string $rule
     * @return bool
     */
    public function hasRule (string $rule): bool {
        $isRule = false;
        foreach ($this->userResources->resources as $ruleCurrent) {
            if ($ruleCurrent == $rule) {
                $isRule = true;
                break;
            }
        }
        return $isRule;
    }

    /**
     * Проверить имеется ли хотя бы одно из правил у пользователя
     * @param array $rules Массив Id правил
     * @return bool
     */
    public function hasAnyRule (array $rules): bool {
        $hasRule = false;
        foreach ($rules as $ruleForChecking) {
            foreach ($this->userResources->resources as $ruleCurrent) {
                if ($ruleCurrent == $ruleForChecking) {
                    $hasRule = true;
                    break;
                }
            }
            if($hasRule)
                break;
        }

        return $hasRule;
    }

    /**
     * Проверить имеются ли каждое из правил у пользователя
     * @param array $rules Массив Id правил
     * @return bool
     */
    public function hasRules (array $rules): bool {
        $hasRule = true;    //Здесь наоборот сначала ставим true
        foreach ($rules as $ruleForChecking) {
            //Нужно подтвердить наличие на каждой итерации
            $hasRuleCurrentIteration = false;
            foreach ($this->userResources->resources as $ruleCurrent) {
                if ($ruleCurrent == $ruleForChecking) {
                    $hasRuleCurrentIteration = true;
                    break;
                }
            }
            //Одного из правил не оказалось - условие не выполнено, выходим
            if(!$hasRuleCurrentIteration) {
                $hasRule = false;
                break;
            }

        }

        return $hasRule;
    }

    /**
     * Флаг подтверждения паспорта
     * @return bool
     */
    public function isPassportConfirmed(): bool
    {
        return $this->isPassportConfirmed;
    }

    /**
     * Задать флаг подтверждения паспорта
     * @param bool $isPassportConfirmed
     */
    public function setIsPassportConfirmed(bool $isPassportConfirmed): void
    {
        $this->isPassportConfirmed = $isPassportConfirmed;
    }


}
