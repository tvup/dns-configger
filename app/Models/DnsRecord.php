<?php

namespace App\Models;

use App\Lib\DigitalOcean\Api\Eloquent\Model;

class DnsRecord extends Model
{
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
}
