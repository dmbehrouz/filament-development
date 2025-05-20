<?php

namespace App\Models;

use App\Services\BaseModel;
use App\Services\Filter\Filter;
use App\Services\Filter\Filterable;
use App\Services\SubjectNormalizer;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Bulletin\Models\Bulletin;
use Modules\Industry\Models\Industry;
use Modules\Keyword\Models\Query;
use Modules\Project\Models\Project;
use Modules\Subject\Services\SubjectFilter\SubjectFilter as SubjectFilterObject;
use Modules\User\Models\Customer;
use Modules\User\Models\User;
use Pishran\LaravelPersianSlug\HasPersianSlug;
use Spatie\Sluggable\SlugOptions;

class SubjectFilter extends Model
{

    protected $casts
        = [
            'items' => 'json',
        ];

    protected $primaryKey = 'subject_id';

    protected $guarded = [ 'subject_id' ];


    public $timestamps = false;


}
