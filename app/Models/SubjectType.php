<?php

namespace App\Models;

use App\Scopes\ShowInDrawerScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class SubjectType extends Model
{
    use SoftDeletes;


    protected $guarded = [ 'id' ];

    protected $dates = [ 'deleted_at' ];


    public function author(): BelongsTo
    {
        return $this->belongsTo( User::class );
    }


    public function subjects(): HasMany
    {
        return $this->hasMany( Subject::class,'type_id');
    }

	protected static function booted()
	{
		static::addGlobalScope(new ShowInDrawerScope());

		static::updating(function($subject){
			self::updateDomainsConfig($subject);
			self::updateCustomersConfig($subject);
		});
	}

	/**
	 * @description Updating domain configs that have the current subject_types
	 * @param SubjectType $subject
	 * @return void
	 */
	private static function updateDomainsConfig(SubjectType $subject):void
	{
		$domains = Domain::query()->orderByDesc('created_at')->get();

		$domains->map(function($domain) use($subject) {
			$domainConfig = json_decode($domain['config'],true);
			$decodedJson['materialThemeSideMenu'] = self::setSideMenuConfig($domainConfig, $subject);
			$domainConfig['materialThemeSideMenu'] = $decodedJson['materialThemeSideMenu'];

			$domain->update([
				'config' => json_encode($domainConfig,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
			]);
		});
	}

	/**
	 * @description Updating customer configs that have the current subject_types
	 * @param SubjectType $subject
	 * @return void
	 */
	private static function updateCustomersConfig(SubjectType $subject):void
	{
		$customerConfigs = CustomerConfig::query()->orderByDesc('created_at')->get();

		$customerConfigs->map(function ($customer) use ($subject) {

			if (!empty($customer['configs']) && !empty($customer['configs']['front.theme.json_config'])) {
				$customerConfigMenuFields = json_decode($customer['configs']['front.theme.json_config'],true);

				if(!empty($customerConfigMenuFields['materialThemeSideMenu'])){
					$config = self::setSideMenuConfig($customerConfigMenuFields, $subject);
					$customerConfigMenuFields['materialThemeSideMenu'] = $config;

					$resultConfig['front.theme.json_config'] = json_encode($customerConfigMenuFields,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
					$customer->update([
						'configs' => $resultConfig
					]);
				}
			}
		});
	}

	/**
	 * @description Change old subject value with current values
	 * @param array $domainConfig
	 * @param SubjectType $subject
	 * @return array
	 */
	private static function setSideMenuConfig(array $domainConfig, SubjectType $subject):array
	{
		return collect($domainConfig['materialThemeSideMenu'])->map(function ($item) use ($subject) {
			if (isset($item['title']) && ($item['title'] === $subject->getOriginal('display_name'))) {
				$item['key'] = $subject->title;
				$item['title'] = $subject->display_name;
				$item['icon'] = $subject->icon_name;
				if($subject->link){
					$item['route'] = "";
					$item['link'] = $subject->link;
				}else{
					unset($item['link']);
					$item['route'] = "/subject/" . $subject->title;
				}
			}
			return $item;
		})->toArray();
	}

	/**
	 * @description Creates the menu structure based on the subject-types ids that are given to it
	 * @param array $subjectIds
	 * @return array
	 */
	public static function createSubjectMenuConfig(array $subjectIds): array
	{
		$orderBy = implode(',', array_map(function ($id) {
			return "'$id'";
		}, $subjectIds));

		return self::withoutGlobalScope(ShowInDrawerScope::class)->whereIn('id', $subjectIds)->orderByRaw("FIELD(id, $orderBy)")->get()->map(function ($item) {
			$result = [
				'key' => $item['title'],
				'title' => $item['display_name'],
				'icon' => $item['icon_name'],
				'route' => "/subject/" . $item['title']
			];
			if(!empty($item['link'])){
				$result['link'] = $item['link'];
				$result['route'] = "";
			}
			return $result;
		})->toArray();
	}

}
