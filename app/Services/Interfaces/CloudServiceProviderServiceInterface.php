<?php

namespace App\Services\Interfaces;

use App\Models\DnsRecord;

interface CloudServiceProviderServiceInterface
{
    public function getDnsRecords() : array;

    public function getDnsRecord(string $id) : object;

    public function deleteDnsRecord(DnsRecord $dnsRecord) : object|null;

    public function createDnsRecord(DnsRecord $dnsRecord): object;

    public function updateDnsRecord(DnsRecord $dnsRecord): object;
}
