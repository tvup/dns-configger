<?php

namespace App\Models;

/**
 * App\Models\DnsRecord.
 *
 * @property string|int $id
 * @property string $zone_id
 * @property string $type
 * @property ?string $name
 * @property ?string $value
 * @property int $ttl
 */
class HetznerDnsRecord extends BaseDnsRecord
{
    protected $fillable = [
        'id',
        'zone_id',
        'type',
        'name',
        'value',
        'ttl',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @param array<string, string|int> $attributes
     * @return void
     */
    public function setAttributes(array $attributes): void
    {
        $this->fill([
            'type' => $attributes['type'] ?? null,
            'name' => $attributes['name'] ?? null,
            'value' => $attributes['data'] ?? null, // 'data' bliver 'value' her
            'ttl' => $attributes['ttl'] ?? 600,
        ]);
    }

    public function getDataAttribute(): string
    {
        return $this->attributes['value'];
    }
}
