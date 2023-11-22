<?php

namespace App\Services\Mocks;

use App\Services\Interfaces\CloudServiceProviderServiceInterface;
use Database\Factories\DnsRecordFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use stdClass;

class CloudServiceProviderServiceMock implements CloudServiceProviderServiceInterface
{
    /**
     * @return array<int, stdClass>
     */
    public function getDnsRecords(): array
    {
        return $this->getDnsRecordsOnFile();
    }

    public function getDnsRecord(int $id): stdClass
    {
        $array = $this->getDnsRecordsOnFile();
        $collection = new Collection();
        foreach ($array as $dnsRecord) {
            $collection->push($dnsRecord);
        }
        /** @var stdClass|null $dnsRecord */
        $dnsRecord = $collection->where('id', $id)->first();
        if (!$dnsRecord) {
            throw new \Exception('Resource not found');
        }

        return $dnsRecord;
    }

    public function deleteDnsRecord(stdClass $dnsRecord): stdClass
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

    public function createDnsRecord(stdClass $dnsRecord): stdClass
    {
        $dnsRecord->id = rand(343248021, 3432480210);
        $array = $this->getDnsRecordsOnFile();
        $array[] = $dnsRecord;

        Storage::disk('test')->put('dns-records.json', json_encode($array));

        return $dnsRecord;
    }

    /**
     * @throws \ErrorException
     */
    public function updateDnsRecord(stdClass $dnsRecord): stdClass
    {
        $array = $this->getDnsRecordsOnFile();
        $collection = collect($array);
        $dnsRecordFromStorage = $collection->where('id', $dnsRecord->id)->first();
        if (!$dnsRecordFromStorage) {
            throw new \Exception('Resource not found');
        }
        foreach (get_object_vars($dnsRecord) as $key => $value) {
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

    /**
     * @return array<stdClass>
     */
    private function getDnsRecordsOnFile() : array
    {
        $dnsRecordsFile = Storage::disk('test')->get('dns-records.json');
        $arrayAway = json_decode($dnsRecordsFile, true);
        // !arrayAway svarer til en fil med indholdet: [] eller ingen fil
        if (!$arrayAway) {
            $array = [];
            for ($i = 0; $i < rand(14, 40); $i++) {
                $dnsRecordArray = app(DnsRecordFactory::class)->definition();
                $dnsRecord = new stdClass();
                foreach ($dnsRecordArray as $key => $value) {
                    $dnsRecord->$key = $value;
                }
                $array[] = $dnsRecord;
            }
            Storage::disk('test')->put('dns-records.json', json_encode($array));
            $dnsRecordsFile = Storage::disk('test')->get('dns-records.json');
            $arrayAway = json_decode($dnsRecordsFile, true);
        }

        $array = [];
        foreach ($arrayAway as $thing) {
            $dnsRecord = new stdClass();
            foreach ($thing as $key => $value) {
                $dnsRecord->$key = $value;
            }
            $array[] = $dnsRecord;
        }

        return $array;
    }
}
