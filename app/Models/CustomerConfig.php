<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use function Modules\User\Models\array_first;


class CustomerConfig extends Model
{

    protected $primaryKey = 'customer_id';
    /**
     * @var string[]
     */
    protected $guarded = [ 'customer_id' ];

    protected $fillable = [ 'configs' ];

    protected $casts = [ 'configs' => 'json' ];

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo( Customer::class );
    }


    public function checkExact( string $setting ): bool
    {
        return isset( $this->configs[ $setting ] ) && $this->configs[ $setting ] === 'on';
    }

    public function check( string $setting ): bool
    {
        $settings = explode( '.', $setting );
        $settingItem = '';
        if ( $result = $this->checkExact( array_first( $settings ) ) ) {
            foreach ( $settings as $item ) {
                $settingItem .= $item;
                $result = $result && $this->checkExact( $settingItem );
                $settingItem .= '.';
            }
            return $result;
        }
        return false;
    }

    public function getValue( string $setting ): mixed
    {
        return $this->configs[ $setting ] ?? null;
    }
}
