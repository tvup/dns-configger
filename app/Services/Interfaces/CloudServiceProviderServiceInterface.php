<?php

namespace App\Services\Interfaces;

use App\Models\DnsRecord;

interface CloudServiceProviderServiceInterface
{
    public function getDnsRecords() : array;

    public function getDnsRecord(string $id) : object;

    public function deleteDnsRecord(DnsRecord|\stdClass $dnsRecord) : object|null;

    public function createDnsRecord(DnsRecord|\stdClass $dnsRecord): object;

    public function updateDnsRecord(DnsRecord|\stdClass $dnsRecord): object;
}
