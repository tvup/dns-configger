<?php

namespace App\Models;

use App\Lib\DigitalOcean\Api\Eloquent\Model;

/**
 * App\Models\DnsRecord.
 *
 * @property string|int $id
 * @property string $type
 * @property string $data
 * @property ?string $name
 * @property int $ttl
 */
class BaseDnsRecord extends Model
{
    protected $fillable = [
        'id',
        'type',
        'data',
        'name',
        'ttl',
    ];

    /**
     * @param array<string, string|int> $attributes
     * @return void
     */
    public function setAttributes(array $attributes): void
    {
        $this->fill($attributes);
    }
}
