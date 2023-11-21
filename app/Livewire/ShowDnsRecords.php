<?php

namespace App\Livewire;

use App\Models\DnsRecord;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('DNS records')]
class ShowDnsRecords extends Component
{
    public $message = '';

    public function delete($dnsRecordId)
    {
        try {
            $model = DnsRecord::find($dnsRecordId);
            $model->delete();
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
        }
    }

    public function edit($id)
    {
        return redirect('/dns-records/edit/' . $id);
    }

    public function render()
    {
        $model = new DnsRecord();
        try {
            $collection = $model->all();
        } catch (\Exception $e) {
            $this->message = 'Error: ' . $e->getMessage();

            return view('livewire.show-dns-records', [
                'dnsRecords' => collect(),
            ]);
        }

        return view('livewire.show-dns-records', [
            'dnsRecords' => $collection,
        ]);
    }
}
