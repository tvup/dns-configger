<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DigitalOceanService
{
    public function getDnsRecords()
    {
        $response = Http::withToken(config('services.digitalocean.api.key'))->get('https://api.digitalocean.com/v2/domains/cdone.dk/records', );
        return json_decode($response->body(), false)->domain_records;
    }

    public function deleteDnsRecord($id) {
        Http::withToken(config('services.digitalocean.api.key'))->delete('https://api.digitalocean.com/v2/domains/cdone.dk/records/' . $id);
        return 'ok';
    }

    public function createDnsRecord($dnsRecord) {
        Http::withToken(config('services.digitalocean.api.key'))->post('https://api.digitalocean.com/v2/domains/cdone.dk/records', [
            'type' => $dnsRecord->type,
            'name' => '@',
            'data' => $dnsRecord->data,
        ]);
        return 'ok';
    }
}