<?php

namespace App\Services;

use App\Services\Interfaces\CloudServiceProviderServiceInterface;
use Illuminate\Support\Facades\Http;
use stdClass;

class HetznerService implements CloudServiceProviderServiceInterface
{
    public const HTTPS_DNS_HETZNER_COM_API = 'https://dns.hetzner.com/api/v1';

    /**
     * @param array<int|string, mixed> $wheres
     * @return array|stdClass[]
     * @throws \Exception
     * @throws \Throwable
     */
    public function getDnsRecords(array $wheres = []) : array
    {
        $hetznerZoneId = config('services.hetzner.zone.id');
        $queryParameters['zone_id'] = $hetznerZoneId;
        $queryParameters = array_merge($queryParameters, $wheres);
        $config = config('services.hetzner.api.key');
        if (!is_string($config)) {
            throw new \Exception('Could not get API key for Hetzner');
        }
        $response = Http::withHeader('Auth-API-Token', $config)->withQueryParameters($queryParameters)->get(
            self::HTTPS_DNS_HETZNER_COM_API . '/records'
        );

        $attribute = 'records';
        $json_decode = $this->getJson_decode($response, 200, 'Error retrieving DNS records', $attribute);
        throw_if(null === $json_decode, new \Exception('Server has unallowed reply'));

        return $json_decode->{$attribute};
    }

    public function getDnsRecord(int|string $id) : stdClass
    {
        $config = config('services.hetzner.api.key');
        if (!is_string($config)) {
            throw new \Exception('Could not get API key for Hetzner');
        }
        $response = Http::withHeader('Auth-API-Token', $config)->get(self::HTTPS_DNS_HETZNER_COM_API . '/records/' . $id);

        $attribute = 'record';
        $json_decode = $this->getJson_decode($response, 200, 'Error retrieving DNS record', $attribute);

        throw_if(null === $json_decode, new \Exception('Server has unallowed reply'));

        return $json_decode->{$attribute};
    }

    public function deleteDnsRecord(stdClass $dnsRecord) : stdClass|null
    {
        $config = config('services.hetzner.api.key');
        if (!is_string($config)) {
            throw new \Exception('Could not get API key for Hetzner');
        }
        $response = Http::withHeader('Auth-API-Token', $config)->delete(self::HTTPS_DNS_HETZNER_COM_API . '/records/' . $dnsRecord->id);

        $json_decode = $this->getJson_decode($response, 200, 'Error deleting DNS record');

        return $json_decode;
    }

    public function createDnsRecord(stdClass $dnsRecord): stdClass
    {
        $config = config('services.hetzner.api.key');
        if (!is_string($config)) {
            throw new \Exception('Could not get API key for Hetzner');
        }

        $hetznerZoneId = config('services.hetzner.zone.id');
        if (!is_string($hetznerZoneId)) {
            throw new \Exception('Could not get Zone id for Hetzner');
        }

        $response = Http::withHeader('Auth-API-Token', $config)->post(
            self::HTTPS_DNS_HETZNER_COM_API . '/records',
            [
                'zone_id' => $hetznerZoneId,
                'type' => $dnsRecord->type ?? null,
                'name' => $dnsRecord->name ?? null,
                'value' => $dnsRecord->value ?? null,
                'ttl' => $dnsRecord->ttl ?? null,
            ]
        );

        $attribute = 'record';
        $json_decode = $this->getJson_decode($response, 200, 'Error creating DNS record', $attribute);
        throw_if(null === $json_decode, new \Exception('Server has unallowed reply'));

        return $json_decode->{$attribute};
    }

    public function updateDnsRecord(stdClass $dnsRecord) : stdClass
    {
        $config = config('services.hetzner.api.key');
        if (!is_string($config)) {
            throw new \Exception('Could not get API key for Hetzner');
        }

        $hetznerZoneId = config('services.hetzner.zone.id');
        if (!is_string($hetznerZoneId)) {
            throw new \Exception('Could not get Zone id for Hetzner');
        }

        $response = Http::withHeader('Auth-API-Token', $config)->put(self::HTTPS_DNS_HETZNER_COM_API . '/records/' . $dnsRecord->id, [
            'zone_id' => $hetznerZoneId,
            'type' => $dnsRecord->type ?? null,
            'name' => $dnsRecord->name ?? null,
            'value' => $dnsRecord->value ?? null,
            'ttl' => $dnsRecord->ttl ?? null,
        ]);

        $attribute = 'record';
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
