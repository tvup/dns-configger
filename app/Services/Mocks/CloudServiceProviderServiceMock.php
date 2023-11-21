<?php

namespace App\Services\Mocks;

use App\Services\Interfaces\CloudServiceProviderServiceInterface;

class CloudServiceProviderServiceMock implements CloudServiceProviderServiceInterface
{
    public function getDnsRecords(): array
    {
        $dnsRecord = new \stdClass();
        $dnsRecord->id = '1';
        $dnsRecord->type = 'A';
        $dnsRecord->name = 'test';
        $dnsRecord->data = '126.0.0.2';
        $dnsRecord->ttl = 300;
        $dnsRecord->priority = null;
        $dnsRecord->port = null;
        $dnsRecord->weight = null;
        $dnsRecord->flags = null;
        $dnsRecord->tag = null;

        return [$dnsRecord];
    }

    public function getDnsRecord(string $id): object
    {
        if ($id == '1') {
            $dnsRecord = new \stdClass();
            $dnsRecord->id = '1';
            $dnsRecord->type = 'A';
            $dnsRecord->name = 'test';
            $dnsRecord->data = '126.0.0.2';
            $dnsRecord->ttl = 300;
            $dnsRecord->priority = null;
            $dnsRecord->port = null;
            $dnsRecord->weight = null;
            $dnsRecord->flags = null;
            $dnsRecord->tag = null;

            return $dnsRecord;
        }
        throw new \Exception('Error');
    }

    public function deleteDnsRecord($dnsRecord): object|null
    {
        if ($dnsRecord->id == '1') {
            $dnsRecord = new \stdClass();
            $dnsRecord->id = '1';
            $dnsRecord->type = 'A';
            $dnsRecord->name = 'test';
            $dnsRecord->data = '126.0.0.2';
            $dnsRecord->ttl = 300;
            $dnsRecord->priority = null;
            $dnsRecord->port = null;
            $dnsRecord->weight = null;
            $dnsRecord->flags = null;
            $dnsRecord->tag = null;

            return $dnsRecord;
        }
        throw new \Exception('Error');
    }

    public function createDnsRecord($dnsRecord): object
    {
        $dnsRecord = new \stdClass();
        $dnsRecord->id = '1';
        $dnsRecord->type = 'A';
        $dnsRecord->name = 'test';
        $dnsRecord->data = '126.0.0.2';
        $dnsRecord->ttl = 300;
        $dnsRecord->priority = null;
        $dnsRecord->port = null;
        $dnsRecord->weight = null;
        $dnsRecord->flags = null;
        $dnsRecord->tag = null;

        return $dnsRecord;
    }

    public function updateDnsRecord($dnsRecord): object
    {
        if ($dnsRecord->id == '1') {
            $dnsRecord = new \stdClass();
            $dnsRecord->id = '1';
            $dnsRecord->type = 'A';
            $dnsRecord->name = 'test';
            $dnsRecord->data = '126.0.0.2';
            $dnsRecord->ttl = 300;
            $dnsRecord->priority = null;
            $dnsRecord->port = null;
            $dnsRecord->weight = null;
            $dnsRecord->flags = null;
            $dnsRecord->tag = null;

            return $dnsRecord;
        }
        throw new \Exception('Error');
    }
}
