<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Chat
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Chat where($field, $value)
 * @mixin Builder
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool|null $active
 * @property int $user_id
 * @property int|null $source
 * @property string|null $snils
 * @property string $client_id ID клиента в том источнике, из которого пришло сообщение
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @method static Builder|Chat newModelQuery()
 * @method static Builder|Chat newQuery()
 * @method static Builder|Chat query()
 * @method static Builder|Chat whereActive($value)
 * @method static Builder|Chat whereClientId($value)
 * @method static Builder|Chat whereCreatedAt($value)
 * @method static Builder|Chat whereId($value)
 * @method static Builder|Chat whereSnils($value)
 * @method static Builder|Chat whereSource($value)
 * @method static Builder|Chat whereUpdatedAt($value)
 * @method static Builder|Chat whereUserId($value)
 * @mixin Eloquent
 */
class Chat extends Model
{
    use HasFactory;

    /**
     * Атрибуты, для которых НЕ разрешено массовое присвоение значений.
     *
     * @var array
     */
    protected $guarded = [];

    public function messages () {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }
}
