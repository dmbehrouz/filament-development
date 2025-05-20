<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SubjectsDailyCategory extends Model
{
	protected $fillable = [ 'name' ];

	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];

	protected $table = 'subjects_daily_categories';

	public function dailySubject()
	{
		return $this->hasMany(SubjectDailyCustomer::class, 'category_id');
	}

	public function subjects()
	{
		return $this->hasManyThrough(
			Subject::class,
			SubjectDailyCustomer::class,
			'category_id',
			'id',
			'id',
			'subject_id'
		);
	}
}
