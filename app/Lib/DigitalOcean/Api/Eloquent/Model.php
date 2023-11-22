<?php

namespace App\Lib\DigitalOcean\Api\Eloquent;

use App\Lib\DigitalOcean\Api\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    protected function newBaseQueryBuilder(): QueryBuilder
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        return new QueryBuilder($conn, $grammar, $conn->getPostProcessor());
    }

    /**
     * @param array<string, mixed> $options
     * @return bool
     * @throws \Exception
     */
    public function save(array $options = [])
    {
        $query = $this->newModelQuery();

        $attributes = $this->attributes;

        if ($this->exists) {
            $query = $this->newBaseQueryBuilder();
            $dirty = $this->getDirty();

            //Here comes the hack :(
            //Some attributes are needed to be considered dirty, even if they are not.
            //This is to ensure that they will be a part of the query that updates the remote entity.
            if ($this instanceof \App\Models\DnsRecord) {
                if (!array_key_exists('name', $dirty)) {
                    $dirty['name'] = $this->name;
                }
                if (($this->type == 'SRV') && (!array_key_exists('data', $dirty)) && ($this->data == '@')) {
                    //This is an extraordinary case only for SRV records.
                    //If data isn't dirty, and the value of data is @,
                    //then we need to set it to @, otherwise digitalocean will set data to something like
                    //example.com.example.com
                    $dirty['data'] = '@';
                }
            }
            //End of hack

            $where = [
                'column' => $this->getKeyName(),
                'value' => $attributes[$this->getKeyName()],
                'operator' => '=',
                'boolean' => 'and',
                'type' => 'Basic',
            ];
            $query->wheres = [$where];

            return $query->update($dirty) > 0;
        }

        $id = $query->insertGetId($attributes, $keyName = $this->getKeyName());
        if ($id) {
            $this->setAttribute($keyName, $id);

            return true;
        } else {
            throw new \Exception('Could not create new record');
        }
    }

    public function delete()
    {
        if (!$this->exists) {
            return false;
        }

        return $this->setKeysForSaveQuery($this->newModelQuery())->delete();
    }
}
