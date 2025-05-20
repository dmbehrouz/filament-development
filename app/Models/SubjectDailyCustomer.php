<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;


/**
 * Modules\Subject\Models\SubjectCustomerPivot
 * @property-read mixed $created_at_fa
 * @property-read mixed $updated_at_fa
 * @property null|SubjectFilter $filters
 * @method static Builder|SubjectCustomerPivot newModelQuery()
 * @method static Builder|SubjectCustomerPivot newQuery()
 * @method static Builder|SubjectCustomerPivot query()
 * @mixin Eloquent
 */
class SubjectDailyCustomer extends Pivot
{
	protected $table = 'subjects_daily_customers';

	protected $fillable = [
		'customer_id',
		'subject_id',
		'category_id',
	];

	public $timestamps = false;

	public function customer(): BelongsTo
	{
		return $this->belongsTo(Customer::class, 'customer_id');
	}

	public function subject(): BelongsTo
	{
		return $this->belongsTo(Subject::class, 'subject_id');
	}

	public function category(): BelongsTo
	{
		return $this->belongsTo(SubjectsDailyCategory::class, 'category_id');
	}
}
