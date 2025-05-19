<?php

namespace App\Models;

use App\Services\BaseModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;


class Customer extends Model
{
    use SoftDeletes;
    use Notifiable;

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    protected $hidden
        = [
            'calendar_uuid',
            'messenger_manager_uuid',
        ];

    /**
     * @var string[]
     */
    protected $appends = ['imageUrl', 'expired'];

    public function domains(): BelongsToMany
    {
        return $this->belongsToMany(Domain::class);
    }

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

}
