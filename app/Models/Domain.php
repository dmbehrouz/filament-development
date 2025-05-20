<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use TomatoPHP\FilamentMediaManager\Traits\InteractsWithMediaFolders;


class Domain extends Model
{
    use SoftDeletes;
    use Notifiable;
    use HasUuids;
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

    public function template()
    {
        return $this->belongsTo(DomainTemplate::class, 'domain_template_id');
    }

}
