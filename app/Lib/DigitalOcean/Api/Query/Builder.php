<?php

namespace App\Lib\DigitalOcean\Api\Query;

use App\Models\DnsRecord;
use App\Services\Interfaces\CloudServiceProviderServiceInterface;
use Illuminate\Support\Collection;
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

    /**
     * @param string[] $columns
     * @return Collection<(int|string), \Illuminate\Database\Eloquent\Model>
     */
    public function get($columns = ['*']): Collection
    {
        $columnIdentifier = is_string($this->from) ? $this->from . '.id' : null;

        if ($columnIdentifier && count($this->wheres) == 1 && $this->wheres[0]['column'] == $columnIdentifier) {
            $dnsRecord = app(CloudServiceProviderServiceInterface::class)->getDnsRecord($this->wheres[0]['value']);
            $collection = new Collection();
            $collection->push($dnsRecord);

            return $collection;
        }

        $queryParameters = [];
        foreach ($this->wheres as $where) {
            if (in_array($where['column'], ['type', 'name'])) {
                $queryParameters[$where['column']] = $where['value'];
            }
        }
        $apiResult = app(CloudServiceProviderServiceInterface::class)->getDnsRecords($queryParameters);

        $collection = new Collection();
        foreach ($apiResult as $dnsRecord) {
            $collection->push($dnsRecord);
        }

        return $collection;
    }

    /**
     * @param array<DnsRecord> $values
     * @return bool
     */
    public function insert(array $values) : bool
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

    public function insertGetId(array $values, $sequence = null) : string|int
    {
        if (empty($values)) {
            return 0;
        }

        $model = new stdClass();
        foreach ($values as $attributeName => $attributeValue) {
            $model->{$attributeName} = $attributeValue;
        }

        $model = app(CloudServiceProviderServiceInterface::class)->createDnsRecord($model);

        return $model->id;
    }

    public function update(array $values): int
    {
        if (empty($values)) {
            return 0;
        }

        $model = new stdClass();
        foreach ($values as $attributeName => $attributeValue) {
            $model->{$attributeName} = $attributeValue;
        }
        $model->id = $this->wheres[0]['value'];
        app(CloudServiceProviderServiceInterface::class)->updateDnsRecord($model);

        return 1;
    }

    /**
     * Is supposed to return the number of rows affected.
     * @param $id
     * @return int
     */
    public function delete($id = null): int
    {
        $model = new stdClass();
        $model->id = $id ?: $this->wheres[0]['value'];
        app(CloudServiceProviderServiceInterface::class)->deleteDnsRecord($model);

        return 1;
    }
}
