<?php

namespace App\Lib\DigitalOcean\Api\Eloquent;

use App\Lib\DigitalOcean\Api\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        return new QueryBuilder($conn, $grammar, $conn->getPostProcessor());
    }

    public function save(array $options = [])
    {
        $query = $this->newModelQuery();

        $attributes = $this->attributes;

        if ($this->exists) {
            $query = $this->newBaseQueryBuilder();
            $dirty = $this->getDirty();

            $where = [
                'column' => $this->getKeyName(),
                'value' => $attributes[$this->getKeyName()],
                'operator' => '=',
                'boolean' => 'and',
                'type' => 'Basic',
            ];
            $query->wheres = [$where];

            return $query->update($dirty);
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
