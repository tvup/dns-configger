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
        $newDnsRecord = new \stdClass();
        $newDnsRecord->id = rand(343248021, 3432480210);
        $newDnsRecord->type = $dnsRecord->type;
        $newDnsRecord->name = $dnsRecord->name;
        $newDnsRecord->data = $dnsRecord->data;
        $newDnsRecord->ttl = $dnsRecord->ttl;
        $newDnsRecord->priority = $dnsRecord->priority;
        $newDnsRecord->port = $dnsRecord->port;
        $newDnsRecord->weight = $dnsRecord->weight;
        $newDnsRecord->flags = $dnsRecord->flags;
        $newDnsRecord->tag = $dnsRecord->tag;

        $array = $this->getDnsRecordsOnFile();

        if (count($array) == 0) {
            $array = [];
            for ($i = 0; $i < rand(1, 40); $i++) {
                $dnsRecord = DnsRecord::factory()->make();
                $array[] = $dnsRecord;
            }
            Storage::disk('test')->put('dns-records.json', json_encode($array));
            $array = $this->getDnsRecordsOnFile();
        }
        $array[] = $newDnsRecord;
        Storage::disk('test')->put('dns-records.json', json_encode($array));

        return $newDnsRecord;
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
