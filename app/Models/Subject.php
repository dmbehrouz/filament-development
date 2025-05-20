<?php

namespace App\Models;

use App\Services\BaseModel;
use App\Services\Filter\Filter;
use App\Services\Filter\Filterable;
use App\Services\SubjectNormalizer;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Subject extends Model
{
	use SoftDeletes;
	use HasPersianSlug;
	use SubjectNormalizer;
	use Filterable;

	protected $casts
		= [
			'is_private' => 'boolean',
			'platforms'  => 'json',
			'categories' => 'json'
		];


	protected $guarded = ['id'];

	protected $appends = ['imageUrl', 'logoUrl', 'hasActiveFilter'];

	protected $with = ['type'];

	protected $dates = ['deleted_at', 'from_date', 'to_date'];



	public function getImageUrlAttribute(): string
	{
		return $this->image ? asset($this->image) : asset('/images/noImage.png');
	}

	public function getLogoUrlAttribute(): string
	{
		return $this->logo ? asset($this->logo) : asset('/images/noImage.png');
	}


	public function author(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}


	public function customers(): BelongsToMany
	{
		return $this->belongsToMany(
			Customer::class,
			'subject_customer',
			'subject_id',
			'customer_id',
		)
			->as('relationValues')
			->withPivot(
				['type', 'platforms', 'from_date', 'to_date']
			)->using(SubjectCustomerPivot::class);
	}

	public function children(): HasMany
	{
		return $this->hasMany(self::class, 'parent_id');
	}

	public function parent(): BelongsTo
	{
		return $this->belongsTo(self::class, 'parent_id');
	}


	public function type(): BelongsTo
	{
		return $this->belongsTo(SubjectType::class,'type_id');
	}

	public function filters(): HasOne
	{
		return $this->hasOne(SubjectFilter::class, 'subject_id');
	}

	public function typeIs($type): bool
	{
		return $this->type->title === $type;
	}

	public function getHasActiveFilterAttribute(): bool
	{
		return $this->filters && (count($this->filters->items['must']) || count($this->filters->items['mustNot']));
	}

	public function dailyCustomers()
	{
		return $this->belongsToMany(
			Customer::class,
			'subjects_daily_customers',
			'subject_id',
			'customer_id'
		)
			->withPivot(['category_id'])
			->withTimestamps()
			->using(SubjectDailyCustomer::class)
			->as('dailyRelation');
	}
}
