<?php

namespace App\Livewire;

use App\Models\DnsRecord;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

#[Title('List DNS records')]
class ShowDnsRecords extends Component
{
    public string $message = '';

    public function delete(int $dnsRecordId) : void
    {
        $this->message = '';
        try {
            $model = DnsRecord::find($dnsRecordId);
            $model->delete();
            $this->message = 'DNS record deleted successfully.';
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
        }
    }

    public function edit(int $id) : \Illuminate\Http\RedirectResponse|Redirector
    {
        return redirect('/dns-records/edit/' . $id);
    }

    public function render() : \Illuminate\View\View
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
