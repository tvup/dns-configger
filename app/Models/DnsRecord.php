<?php

namespace App\Models;

use App\Services\Interfaces\CloudServiceProviderServiceInterface;

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

    public function all()
    {
        $array = [];
        foreach (app(CloudServiceProviderServiceInterface::class)->getDnsRecords() as $dnsRecord) {
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

    public function find($id)
    {
        $dnsRecord = app(CloudServiceProviderServiceInterface::class)->getDnsRecord($id);
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

        return $model;
    }

    public function delete()
    {
        app(CloudServiceProviderServiceInterface::class)->deleteDnsRecord($this);
    }

    public function create()
    {
        $jsonObject = app(CloudServiceProviderServiceInterface::class)->createDnsRecord($this);
        $this->id = $jsonObject->id;
        $this->type = $jsonObject->type;
        $this->name = $jsonObject->name;
        $this->data = $jsonObject->data;
        $this->priority = $jsonObject->priority;
        $this->port = $jsonObject->port;
        $this->ttl = $jsonObject->ttl;
        $this->weight = $jsonObject->weight;
        $this->flags = $jsonObject->flags;
        $this->tag = $jsonObject->tag;
    }

    public function update()
    {
        $jsonObject = app(CloudServiceProviderServiceInterface::class)->updateDnsRecord($this);
        $this->id = $jsonObject->id;
        $this->type = $jsonObject->type;
        $this->name = $jsonObject->name;
        $this->data = $jsonObject->data;
        $this->priority = $jsonObject->priority;
        $this->port = $jsonObject->port;
        $this->ttl = $jsonObject->ttl;
        $this->weight = $jsonObject->weight;
        $this->flags = $jsonObject->flags;
        $this->tag = $jsonObject->tag;
    }
}
