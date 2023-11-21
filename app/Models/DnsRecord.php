<?php

namespace App\Models;

use App\Services\DigitalOceanService;

class DnsRecord
{
    public $id;
    public $type;
    public $name;
    public $data;
    public $priority;
    public $port;
    public $ttl;
    public $weight;
    public $flags;
    public $tag;


    public function all() {
        $array = [];
        foreach(app(DigitalOceanService::class)->getDnsRecords() as $dnsRecord) {
            $model = new DnsRecord();
            $model->id = $dnsRecord->id;
            $model->type = $dnsRecord->type;
            $model->name = $dnsRecord->name;
            $model->data = $dnsRecord->data;
            $model->priority = $dnsRecord->priority;
            $model->port = $dnsRecord->port;
            $model->ttl = $dnsRecord->ttl;
            $model->weight = $dnsRecord->weight;
            $model->flags = $dnsRecord->flags;
            $model->tag = $dnsRecord->tag;
            $array[] = $model;
        }
        return collect($array);
    }

    public function delete($id) {
        app(DigitalOceanService::class)->deleteDnsRecord($id);
    }

    public function create() {
        app(DigitalOceanService::class)->createDnsRecord($this);
    }
}
