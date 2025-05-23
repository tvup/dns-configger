<?php

namespace App\Console\Commands;

use App\Services\Interfaces\CloudServiceProviderServiceInterface;
use Illuminate\Console\Command;

class ProviderDnsRetrieve extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'provider:retrieve-dns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle() : void
    {
        dd(app(CloudServiceProviderServiceInterface::class)->getDnsRecords());
    }
}
