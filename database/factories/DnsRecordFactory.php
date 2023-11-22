<?php

namespace Database\Factories;

use App\Models\DnsRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DnsRecordFactory extends Factory
{
    protected $model = DnsRecord::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['A', 'AAAA', 'CAA', 'CNAME', 'MX', 'NS', 'SRV', 'TXT','SOA']);
        $name = null;
        $data = null;
        $priority = null;
        $port = null;
        $weight = null;
        $flags = null;
        $tag = null;
        switch($type) {
            case 'A':
                $name = Str::lower($this->faker->firstName());
                $data = $this->faker->ipv4();
                break;
            case 'AAAA':
                $name = Str::lower($this->faker->firstName());
                $data = $this->faker->ipv6();
                break;
            case 'CAA':
                $name = Str::lower($this->faker->firstName());
                $flags = $this->faker->numberBetween(0,255);
                $tag = $this->faker->randomElement(['issue', 'issuewild', 'iodef']);
                switch($tag) {
                    case 'issue':
                    case 'issuewild':
                        $data = $this->faker->domainName();
                        break;
                    case 'iodef':
                        $data = $this->faker->url();
                        break;
                    default:
                        break;
                }
                break;
            case 'CNAME':
                $name = Str::lower($this->faker->firstName());
                $data = $this->faker->firstName;
                break;
            case 'MX':
                $name = Str::lower($this->faker->firstName());
                $data = 'mx.'.$this->faker->domainName();
                $priority = $this->faker->randomElement([10, 20, 39, 50]);
                break;
            case 'NS':
                $name = Str::lower($this->faker->firstName());
                $data = $this->faker->domainName();
                break;
            case 'SRV':
                $name = ' _service._protocol';
                $data = 'www';
                $priority = $this->faker->randomElement([10, 20, 39, 50]);
                $port = $this->faker->randomElement([5010, 6020, 1139, 5050]);
                $weight = $this->faker->randomElement([1,2,3,4]);
                break;
            case 'TXT':
                $name = Str::lower($this->faker->firstName());
                $data = $this->faker->text();
                break;
            case 'SOA':
                $aNumber = $this->faker->numberBetween(1,604800);
                $name = '@';
                $data = $aNumber;
                $ttl = $aNumber;
                break;
            default:
                break;
        }
        return [
            'id' => $this->faker->numberBetween(343233992,3432339902),
            'type' => $type,
            'name' => $name,
            'data' => $data,
            'priority' => $priority,
            'port' => $port,
            'weight' => $weight,
            'flags' => $flags,
            'tag' => $tag,
            'ttl' => $ttl ?? $this->faker->numberBetween(1,604800),
        ];
    }
}
