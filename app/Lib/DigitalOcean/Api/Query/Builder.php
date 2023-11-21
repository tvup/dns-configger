<?php

namespace App\Lib\DigitalOcean\Api\Query;

use App\Services\Interfaces\CloudServiceProviderServiceInterface;
use stdClass;

class Builder extends \Illuminate\Database\Query\Builder
{
    public $operators = [
        '=',
    ];

    public function __construct(
        \Illuminate\Database\ConnectionInterface $connection,
        \Illuminate\Database\Query\Grammars\Grammar $grammar,
        \Illuminate\Database\Query\Processors\Processor $processor
    ) {
        parent::__construct($connection, $grammar, $processor);
    }

    public function get($columns = ['*'])
    {
        if (count($this->wheres) == 1 && $this->wheres[0]['column'] == $this->from . '.id') {
            $dnsRecord = app(CloudServiceProviderServiceInterface::class)->getDnsRecord($this->wheres[0]['value']);

            $array = [];
            $model = [];
            $model['id'] = $dnsRecord->id;
            $model['type'] = $dnsRecord->type;
            $model['name'] = $dnsRecord->name;
            $model['data'] = $dnsRecord->data;
            $model['priority'] = $dnsRecord->priority;
            $model['port'] = $dnsRecord->port;
            $model['ttl'] = $dnsRecord->ttl;
            $model['weight'] = $dnsRecord->weight;
            $model['flags'] = $dnsRecord->flags;
            $model['tag'] = $dnsRecord->tag;
            $array[] = $model;

            return collect($array);
        }

        $queryParameters = [];
        foreach ($this->wheres as $where) {
            if (in_array($where['column'], ['type', 'name'])) {
                $queryParameters[$where['column']] = $where['value'];
            }
        }
        $apiResult = app(CloudServiceProviderServiceInterface::class)->getDnsRecords($queryParameters);

        $array = [];
        foreach ($apiResult as $dnsRecord) {
            $model = [];
            $model['id'] = $dnsRecord->id;
            $model['type'] = $dnsRecord->type;
            $model['name'] = $dnsRecord->name;
            $model['data'] = $dnsRecord->data;
            $model['priority'] = $dnsRecord->priority;
            $model['port'] = $dnsRecord->port;
            $model['ttl'] = $dnsRecord->ttl;
            $model['weight'] = $dnsRecord->weight;
            $model['flags'] = $dnsRecord->flags;
            $model['tag'] = $dnsRecord->tag;
            $array[] = $model;
        }

        return collect($array);
    }

    public function insert(array $values)
    {
        if (empty($values)) {
            return false;
        }

        $model = new stdClass();
        foreach ($values as $attributeName => $attributeValue) {
            $model->{$attributeName} = $attributeValue;
        }

        app(CloudServiceProviderServiceInterface::class)->createDnsRecord($model);

        return true;
    }

    public function insertGetId(array $values, $sequence = null)
    {
        if (empty($values)) {
            return false;
        }

        $model = new stdClass();
        foreach ($values as $attributeName => $attributeValue) {
            $model->{$attributeName} = $attributeValue;
        }

        $model = app(CloudServiceProviderServiceInterface::class)->createDnsRecord($model);

        return $model->id;
    }

    public function update(array $values)
    {
        if (empty($values)) {
            return false;
        }

        $model = new stdClass();
        foreach ($values as $attributeName => $attributeValue) {
            $model->{$attributeName} = $attributeValue;
        }
        $model->id = $this->wheres[0]['value'];
        app(CloudServiceProviderServiceInterface::class)->updateDnsRecord($model);

        return true;
    }

    public function delete($id = null)
    {
        $model = new stdClass();
        $model->id = $id ?: $this->wheres[0]['value'];
        app(CloudServiceProviderServiceInterface::class)->deleteDnsRecord($model);

        return true;
    }
}
