<?php

namespace App\Services\Interfaces;

use stdClass;

interface CloudServiceProviderServiceInterface
{
    /**
     * @return array<int, stdClass>
     */
    public function getDnsRecords() : array;

    public function getDnsRecord(int $id) : stdClass;

    public function deleteDnsRecord(stdClass $dnsRecord) : stdClass|null;

    public function createDnsRecord(stdClass $dnsRecord): stdClass;

    public function updateDnsRecord(stdClass $dnsRecord): stdClass;
}
