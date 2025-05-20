<?php

namespace App\Models;

use App\Enums\SubjectRelationEnum;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;


/**
 * Modules\Subject\Models\SubjectCustomerPivot
 * @property null|SubjectFilter $filters
 * @method static Builder|SubjectCustomerPivot newModelQuery()
 * @method static Builder|SubjectCustomerPivot newQuery()
 * @method static Builder|SubjectCustomerPivot query()
 * @mixin Eloquent
 */
class SubjectCustomerPivot extends Pivot
{
	protected $table = 'subject_customer';

	protected $casts
		= [
			'is_private'     => 'boolean',
			'platforms'      => 'json',
			'must_not_terms' => 'json',
			'must_terms'     => 'json',
			'type'           => SubjectRelationEnum::class,
		];

	protected $dates = ['from_date', 'to_date'];

	public function dailyCategory()
	{
		return $this->belongsTo(SubjectsDailyCategory::class, 'is_daily_subject', 'id');
	}
}
