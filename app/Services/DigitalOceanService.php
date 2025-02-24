<?php

namespace App\Services;

use App\Services\Interfaces\CloudServiceProviderServiceInterface;
use Illuminate\Support\Facades\Http;
use stdClass;

class DigitalOceanService implements CloudServiceProviderServiceInterface
{
    /**
     * @param array<int|string, mixed> $wheres
     * @return array|stdClass[]
     * @throws \Exception
     * @throws \Throwable
     */
    public function getDnsRecords(array $wheres = []) : array
    {
        $queryParameters['per_page'] = '100';
        $queryParameters = array_merge($queryParameters, $wheres);
        $config = config('services.digitalocean.api.key');
        if (!is_string($config)) {
            throw new \Exception('Could not get API key for DigitalOcean');
        }
        $response = Http::withToken($config)->withQueryParameters($queryParameters)->get('https://api.digitalocean.com/v2/domains/' . config('services.digitalocean.domain.url') . '/records', );

        $attribute = 'domain_records';
        $json_decode = $this->getJson_decode($response, 200, 'Error retrieving DNS records', $attribute);
        throw_if(null === $json_decode, new \Exception('Server has unallowed reply'));

        return $json_decode->{$attribute};
    }

    public function getDnsRecord(int|string $id) : stdClass
    {
        $config = config('services.digitalocean.api.key');
        if (!is_string($config)) {
            throw new \Exception('Could not get API key for DigitalOcean');
        }
        $response = Http::withToken($config)->get('https://api.digitalocean.com/v2/domains/' . config('services.digitalocean.domain.url') . '/records/' . $id);

        $attribute = 'domain_record';
        $json_decode = $this->getJson_decode($response, 200, 'Error retrieving DNS record', $attribute);
        throw_if(null === $json_decode, new \Exception('Server has unallowed reply'));

        return $json_decode->{$attribute};
    }

    public function deleteDnsRecord(stdClass $dnsRecord) : stdClass|null
    {
        $config = config('services.digitalocean.api.key');
        if (!is_string($config)) {
            throw new \Exception('Could not get API key for DigitalOcean');
        }
        $response = Http::withToken($config)->delete('https://api.digitalocean.com/v2/domains/' . config('services.digitalocean.domain.url') . '/records/' . $dnsRecord->id);

        $json_decode = $this->getJson_decode($response, 204, 'Error deleting DNS record');

        return $json_decode;
    }

    public function createDnsRecord(stdClass $dnsRecord): stdClass
    {
        $priority = null;
        if (isset($dnsRecord->priority) && $dnsRecord->priority != '') {
            $priority = $dnsRecord->priority;
        }
        $config = config('services.digitalocean.api.key');
        if (!is_string($config)) {
            throw new \Exception('Could not get API key for DigitalOcean');
        }

        $response = Http::withToken($config)->post('https://api.digitalocean.com/v2/domains/' . config('services.digitalocean.domain.url') . '/records', [
            'type' => $dnsRecord->type ?? null,
            'name' => $dnsRecord->name ?? null,
            'data' => $dnsRecord->data ?? null,
            'priority' => $priority,
            'port' => $dnsRecord->port ?? null,
            'ttl' => $dnsRecord->ttl ?? null,
            'weight' => $dnsRecord->weight ?? null,
            'flags' => $dnsRecord->flags ?? null,
            'tag' => $dnsRecord->tag ?? null,
        ]);

        $attribute = 'domain_record';
        $json_decode = $this->getJson_decode($response, 201, 'Error creating DNS record', $attribute);
        throw_if(null === $json_decode, new \Exception('Server has unallowed reply'));

        return $json_decode->{$attribute};
    }

    public function updateDnsRecord(stdClass $dnsRecord) : stdClass
    {
        $priority = null;
        if (isset($dnsRecord->priority) && $dnsRecord->priority != '') {
            $priority = $dnsRecord->priority;
        }
        $config = config('services.digitalocean.api.key');
        if (!is_string($config)) {
            throw new \Exception('Could not get API key for DigitalOcean');
        }
        $response = Http::withToken($config)->put('https://api.digitalocean.com/v2/domains/' . config('services.digitalocean.domain.url') . '/records/' . $dnsRecord->id, [
            'type' => $dnsRecord->type ?? null,
            'name' => $dnsRecord->name ?? null,
            'data' => $dnsRecord->data ?? null,
            'priority' => $priority,
            'port' => $dnsRecord->port ?? null,
            'ttl' => $dnsRecord->ttl ?? null,
            'weight' => $dnsRecord->weight ?? null,
            'flags' => $dnsRecord->flags ?? null,
            'tag' => $dnsRecord->tag ?? null,
        ]);

        $attribute = 'domain_record';
        $json_decode = $this->getJson_decode($response, 200, 'Error updating DNS record', $attribute);
        throw_if(null === $json_decode, new \Exception('Server has unallowed reply'));

        return $json_decode->{$attribute};
    }

    /**
     * @param \Illuminate\Http\Client\Response $response
     * @return ?stdClass
     * @throws \Exception
     */
    private function getJson_decode(
        \Illuminate\Http\Client\Response $response,
        int $statusCode,
        string $fallBackMessage,
        ?string $attribute = null
    ) {
        $body = $response->body();

        if ($statusCode != 204 && '' == $body) {
            throw new \Exception('4A ' . $fallBackMessage);
        }

        $json_decode = null;

        if ($attribute) {
            /** @var stdClass|false|null $json_decode */
            $json_decode = json_decode($body, false);
            if (null === $json_decode || $json_decode === false) {
                throw new \Exception('4B ' . $fallBackMessage);
            }
        }

        if ($response->status() != $statusCode) {
            /** @var stdClass|false|null $json_decode */
            $json_decode = json_decode($body, false);
            if (null === $json_decode || $json_decode === false || !isset($json_decode->message)) {
                throw new \Exception('4C ' . $fallBackMessage);
            }
            throw new \Exception($json_decode->message);
        }

        if ($attribute && !isset($json_decode->{$attribute})) {
            throw new \Exception('4D ' . $fallBackMessage);
        }

        return $json_decode;
    }
}
