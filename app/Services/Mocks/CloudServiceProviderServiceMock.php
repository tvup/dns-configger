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
     * @param array<int|string, mixed> $wheres
     * @return array<int, stdClass>
     * @throws \ErrorException
     */
    public function getDnsRecords(array $wheres = []): array
    {
        return $this->getDnsRecordsOnFile();
    }

    /**
     * @throws \Exception
     */
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

    /**
     * @throws \ErrorException
     */
    public function deleteDnsRecord(stdClass $dnsRecord): stdClass
    {
        $array = $this->getDnsRecordsOnFile();
        $collection = collect($array);

        foreach ($collection as $key => $item) {
            if ($item->id == $dnsRecord->id) {
                unset($collection[$key]);
            }
        }

        /** @var array<stdClass> $array1 */
        $array1 = $collection->toArray();
        $this->saveModelsToFileOrFail($array1);

        return $dnsRecord;
    }

    /**
     * @throws \ErrorException
     */
    public function createDnsRecord(stdClass $dnsRecord): stdClass
    {
        $dnsRecord->id = rand(343248021, 3432480210);
        $array = $this->getDnsRecordsOnFile();
        $array[] = $dnsRecord;

        $this->saveModelsToFileOrFail($array);

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

        /** @var array<stdClass> $array1 */
        $array1 = $collection->toArray();
        $this->saveModelsToFileOrFail($array1);

        return $dnsRecordFromStorage;
    }

    /**
     * @return array<stdClass>
     * @throws \ErrorException
     */
    private function getDnsRecordsOnFile() : array
    {
        $dnsRecordsFile = Storage::disk('test')->get('dns-records.json');
        if (!$dnsRecordsFile) {
            throw new \ErrorException('Could not load file with mocked data');
        }
        /** @var array<array<string,int|string|null>>|false|null $arrayAway */
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
            $this->saveModelsToFileOrFail($array);
        }

        $array = [];
        if (!$arrayAway) {
            throw new \ErrorException('Could not parse file with mocked data');
        }
        foreach ($arrayAway as $thing) {
            $dnsRecord = new stdClass();
            foreach ($thing as $key => $value) {
                $dnsRecord->$key = $value;
            }
            $array[] = $dnsRecord;
        }

        return $array;
    }

    /**
     * @param array<stdClass> $array
     * @return void
     * @throws \ErrorException
     */
    public function saveModelsToFileOrFail(array $array): void
    {
        $json_encode = json_encode($array);
        if ($json_encode) {
            Storage::disk('test')->put('dns-records.json', $json_encode);
        } else {
            throw new \ErrorException('Could not create file to store mock data in');
        }
    }
}
