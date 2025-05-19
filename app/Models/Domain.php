<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;


class Domain extends Model
{
    use SoftDeletes;
    use Notifiable;

    protected $fillable = [
        'id',
        'domain',
        'slug',
        'name',
        'description',
        'config',
        'logo',
        'background',
        'theme_css',
        'theme_js',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class);
    }

}
