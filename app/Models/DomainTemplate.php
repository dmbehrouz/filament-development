<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class DomainTemplate extends Model
{
    protected $table = 'domain_template';

    protected $fillable = [
        'name',
        'config',
        'theme_css',
        'theme_js'
    ];

    public function domain()
    {
        return $this->hasMany(Domain::class, 'domain_template_id');
    }

}
