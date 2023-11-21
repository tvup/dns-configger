<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DigitalOceanService implements Interfaces\CloudServiceProviderServiceInterface
{
    public function getDnsRecords() : array
    {
        $response = Http::withToken(config('services.digitalocean.api.key'))->withQueryParameters(['per_page'=>'100'])->get('https://api.digitalocean.com/v2/domains/' . config('services.digitalocean.domain.url') . '/records', );

        $attribute = 'domain_records';
        $json_decode = $this->getJson_decode($response, 200, 'Error retrieving DNS records', $attribute);

        return $json_decode->{$attribute};
    }

    public function getDnsRecord($id) : object
    {
        $response = Http::withToken(config('services.digitalocean.api.key'))->get('https://api.digitalocean.com/v2/domains/' . config('services.digitalocean.domain.url') . '/records/' . $id);

        $attribute = 'domain_record';
        $json_decode = $this->getJson_decode($response, 200, 'Error retrieving DNS record', $attribute);

        return $json_decode->{$attribute};
    }

    public function deleteDnsRecord($dnsRecord) : object|null
    {
        $response = Http::withToken(config('services.digitalocean.api.key'))->delete('https://api.digitalocean.com/v2/domains/' . config('services.digitalocean.domain.url') . '/records/' . $dnsRecord->id);

        $json_decode = $this->getJson_decode($response, 204, 'Error deleting DNS record');

        return $json_decode;
    }

    public function createDnsRecord($dnsRecord): object
    {
        $response = Http::withToken(config('services.digitalocean.api.key'))->post('https://api.digitalocean.com/v2/domains/' . config('services.digitalocean.domain.url') . '/records', [
            'type' => $dnsRecord->type,
            'name' => $dnsRecord->name,
            'data' => $dnsRecord->data,
            'priority' => $dnsRecord->priority != '' ? $dnsRecord->priority : null,
            'port' => $dnsRecord->port,
            'ttl' => $dnsRecord->ttl,
            'weight' => $dnsRecord->weight,
            'flags' => $dnsRecord->flags,
            'tag' => $dnsRecord->tag,
        ]);

        $attribute = 'domain_record';
        $json_decode = $this->getJson_decode($response, 201, 'Error creating DNS record', $attribute);

        return $json_decode->{$attribute};
    }

    public function updateDnsRecord($dnsRecord) : object
    {
        $response = Http::withToken(config('services.digitalocean.api.key'))->put('https://api.digitalocean.com/v2/domains/' . config('services.digitalocean.domain.url') . '/records/' . $dnsRecord->id, [
            'type' => $dnsRecord->type,
            'name' => $dnsRecord->name,
            'data' => $dnsRecord->data,
            'priority' => $dnsRecord->priority != '' ? $dnsRecord->priority : null,
            'port' => $dnsRecord->port,
            'ttl' => $dnsRecord->ttl,
            'weight' => $dnsRecord->weight,
            'flags' => $dnsRecord->flags,
            'tag' => $dnsRecord->tag,
        ]);

        $attribute = 'domain_record';
        $json_decode = $this->getJson_decode($response, 200, 'Error updating DNS record', $attribute);

        return $json_decode->{$attribute};
    }

    /**
     * @param \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response $response
     * @return mixed
     * @throws \Exception
     */
    private function getJson_decode(
        \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response $response,
        int $statusCode,
        string $fallBackMessage,
        string $attribute = null
    ) {
        $body = $response->body();

        if (null === $body) {
            throw new \Exception($fallBackMessage);
        }

        $json_decode = null;

        if ($attribute) {
            $json_decode = json_decode($body, false);
            if (null === $json_decode) {
                throw new \Exception($fallBackMessage);
            }
        }

        if ($response->status() != $statusCode) {
            $json_decode = json_decode($body, false);
            if (null === $json_decode || !isset($json_decode->message)) {
                throw new \Exception($fallBackMessage);
            }
            throw new \Exception($json_decode->message);
        }

        if ($attribute && !isset($json_decode->{$attribute})) {
            throw new \Exception($fallBackMessage);
        }

        return $json_decode;
    }
}
