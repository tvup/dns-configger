<?php

namespace App\Models;

use Database\Factories\DnsRecordFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\DnsRecord.
 *
 * @property int $id
 * @property string $type
 * @property ?string $name
 * @property ?string $data
 * @property ?int $priority
 * @property ?int $port
 * @property ?int $weight
 * @property ?int $flags
 * @property ?string $tag
 * @property int $ttl
 */
class DnsRecord extends BaseDnsRecord
{
    /** @use HasFactory<DnsRecordFactory> */
    use HasFactory;

    protected $fillable = [
        'id',
        'type',
        'name',
        'data',
        'ttl',
        'priority',
        'port',
        'weight',
        'flags',
        'tag',
    ];

    /**
     * @param array<string, string|int> $attributes
     * @return void
     */
    public function setAttributes(array $attributes): void
    {
        $this->fill([
            'type' => $attributes['type'] ?? null,
            'name' => $attributes['name'] ?? null,
            'data' => $attributes['data'] ?? null, // Brug 'data'
            'ttl' => $attributes['ttl'] ?? 600,
        ]);
    }
}
