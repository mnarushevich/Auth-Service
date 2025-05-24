<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $street_address
 * @property string $address_line_2
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $postal_code
 * @property float $latitude
 * @property float $longitude
 * @property string $user_uuid
 */
class Address extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'street_address',
        'address_line_2',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'user_uuid',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
