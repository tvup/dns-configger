<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\DnsRecord;

#[Title('DNS records')]
class ShowDnsRecords extends Component
{

    public function delete($dnsRecordId)
    {
        $model = new DnsRecord();
        $model->delete($dnsRecordId);
    }


    public function render()
    {
        $model = new DnsRecord();
        return view('livewire.show-dns-records', [
            'dnsRecords' => $model->all(),
        ]);
    }
}
