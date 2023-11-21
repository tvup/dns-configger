<?php

namespace App\Console\Commands;

use App\Services\DigitalOceanService;
use Illuminate\Console\Command;

class DigitalOceanDnsRetrieve extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'digitalocean:retrieve-dns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dd(app(DigitalOceanService::class)->getDnsRecords());
    }
}
