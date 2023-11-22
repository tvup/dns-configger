<?php

namespace App\Models;

use App\Lib\DigitalOcean\Api\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

/**
 * App\Models\DnsRecord.
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string $data
 * @property int $priority
 * @property int $port
 * @property int $weight
 * @property int $flags
 * @property string $tag
 * @property int $ttl
 */
class DnsRecord extends Model
{
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

    public function getDataAttribute()
    {
        $data = $this->attributes['data'];
        switch($this->type) {
            case 'MX':
                $data = Str::replaceLast('.' . config('services.digitalocean.domain.url'), '', $data);
                $data = rtrim($data, '.');
                break;
            case 'CAA':
            case 'NS':
                $data = rtrim($data, '.');
                break;
            case 'CNAME':
                if ($data == '@') {
                    $data = config('services.digitalocean.domain.url');
                } else {
                    $data = rtrim($data, '.');
                }
                break;
            default:
                break;
        }

        return $data;
    }

    public function setDataAttribute($value)
    {
        switch($this->type) {
            case 'CAA':
            case 'CNAME':
            case 'MX':
            case 'NS':
                if ($value == '@') {
                    //nop
                } else {
                    $value = Str::finish($value, '.');
                }
                break;

            default:
                break;
        }
        $this->attributes['data'] = $value;
    }
}
