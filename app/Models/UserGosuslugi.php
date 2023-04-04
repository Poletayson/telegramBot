<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\UserGosuslugi
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserGosuslugi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserGosuslugi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserGosuslugi query()
 * @mixin \Eloquent
 */
class UserGosuslugi extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var string|null Фамилия
     */
    private ?string $surname;

    /**
     * @var string|null Имя
     */
    private ?string $name;

    /**
     * @var string|null Отчество
     */
    private ?string $patronymic;

    /**
     * @var string|null День рождения
     */
    private ?string $birthday;

    /**
     * @var string|null СНИЛС
     */
    private ?string $SNILS;

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string|null $surname
     */
    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    /**
     * @param string|null $patronymic
     */
    public function setPatronymic(?string $patronymic): void
    {
        $this->patronymic = $patronymic;
    }

    /**
     * @return string|null День рождения
     */
    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    /**
     * @param string|null $birthday День рождения
     */
    public function setBirthday(?string $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string|null СНИЛС
     */
    public function getSNILS(): ?string
    {
        return $this->SNILS;
    }

    /**
     * @param string|null $SNILS СНИЛС
     */
    public function setSNILS(?string $SNILS): void
    {
        $this->SNILS = $SNILS;
    }


//    /**
//     * The attributes that are mass assignable.
//     *
//     * @var array<int, string>
//     */
//    protected $fillable = [
//        'name',
//        'email',
//        'password',
//    ];
//
//    /**
//     * The attributes that should be hidden for serialization.
//     *
//     * @var array<int, string>
//     */
//    protected $hidden = [
//        'password',
//        'remember_token',
//    ];
//
//    /**
//     * The attributes that should be cast.
//     *
//     * @var array<string, string>
//     */
//    protected $casts = [
//        'email_verified_at' => 'datetime',
//    ];


}
