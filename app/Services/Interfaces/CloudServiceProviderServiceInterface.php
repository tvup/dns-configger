<?php

namespace App\Services\Interfaces;

use stdClass;

interface CloudServiceProviderServiceInterface
{
    /**
     * @param array<int|string, mixed> $wheres
     * @return array<int, stdClass>
     */
    public function getDnsRecords(array $wheres = []) : array;

    public function getDnsRecord(int|string $id) : stdClass;

    public function deleteDnsRecord(stdClass $dnsRecord) : stdClass|null;

    public function createDnsRecord(stdClass $dnsRecord): stdClass;

    public function updateDnsRecord(stdClass $dnsRecord): stdClass;
}
