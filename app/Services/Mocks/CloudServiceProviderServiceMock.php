<?php

namespace App\Services\Mocks;

use App\Models\DnsRecord;
use App\Services\Interfaces\CloudServiceProviderServiceInterface;
use Illuminate\Support\Facades\Storage;

class CloudServiceProviderServiceMock implements CloudServiceProviderServiceInterface
{
    public function getDnsRecords(): array
    {
        $array = $this->getDnsRecordsOnFile();

        if (count($array) == 0) {
            $array = [];
            for ($i = 0; $i < rand(1, 40); $i++) {
                $dnsRecord = DnsRecord::factory()->make();
                $array[] = $dnsRecord;
            }
            Storage::disk('test')->put('dns-records.json', json_encode($array));

            return $array;
        }

        return $array;
    }

    public function getDnsRecord(string $id): object
    {
        $array = $this->getDnsRecordsOnFile();
        $collection = collect($array);
        $dnsRecord = $collection->where('id', $id)->first();
        if (!$dnsRecord) {
            throw new \Exception('Resource not found');
        }

        return $dnsRecord;
    }

    public function deleteDnsRecord($dnsRecord): object|null
    {
        $array = $this->getDnsRecordsOnFile();
        $collection = collect($array);

        foreach ($collection as $key => $item) {
            if ($item->id == $dnsRecord->id) {
                unset($collection[$key]);
            }
        }

        Storage::disk('test')->put('dns-records.json', json_encode($collection->toArray()));

        return $dnsRecord;
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
        $array = $this->getDnsRecordsOnFile();
        $collection = collect($array);
        $dnsRecordFromStorage = $collection->where('id', $dnsRecord->id)->first();
        if (!$dnsRecordFromStorage) {
            throw new \Exception('Resource not found');
        }
        foreach ($dnsRecord as $key => $value) {
            $dnsRecordFromStorage->{$key} = $dnsRecord->{$key};
        }

        foreach ($collection as $key => $item) {
            if ($item->id == $dnsRecord->id) {
                $collection[$key] = $dnsRecordFromStorage;
            }
        }

        Storage::disk('test')->put('dns-records.json', json_encode($collection->toArray()));

        return $dnsRecordFromStorage;
    }

    public function getDnsRecordsOnFile()
    {
        $dnsRecordsFile = Storage::disk('test')->get('dns-records.json');
        $arrayAway = json_decode($dnsRecordsFile, true);
        if (!$arrayAway) {
            return [];
        }
        $array = [];
        foreach ($arrayAway as $thing) {
            $dnsRecord = new DnsRecord();
            $dnsRecord->forceFill($thing);
            $array[] = $dnsRecord;
        }

        return $array;
    }
}
