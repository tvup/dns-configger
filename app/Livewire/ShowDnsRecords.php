<?php

namespace App\Livewire;

use App\Models\BaseDnsRecord;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

#[Title('List DNS records')]
class ShowDnsRecords extends Component
{
    private ?BaseDnsRecord $dnsRecord = null;

    private function getDnsRecord(): BaseDnsRecord
    {
        if (!$this->dnsRecord) {
            $this->dnsRecord = App::make(BaseDnsRecord::class);
        }

        return $this->dnsRecord;
    }

    public function delete(int|string $dnsRecordId) : void
    {
        try {
            $model = $this->getDnsRecord();
            $model = $model->find($dnsRecordId);
            if (!$model) {
                throw new \Exception('DNS record not found.');
            }
            $model->delete();
            $this->dispatch('info', type: 'info', message: 'DNS record deleted successfully.');
        } catch (\Exception $e) {
            $this->dispatch('error', type: 'error', message: 'Error: ' . $e->getMessage() . ' <br /><button type="button" class="btn clear">Dismiss</button>', title: 'Error during deletion');
        }
    }

    public function edit(int|string $id) : \Illuminate\Http\RedirectResponse|Redirector
    {
        return redirect('/dns-records/edit/' . $id);
    }

    public function render() : \Illuminate\View\View
    {
        $model = $this->getDnsRecord();
        try {
            $collection = $model->all();
        } catch (\Exception $e) {
            $this->dispatch('error', type: 'error', message: 'Error: ' . $e->getMessage() . ' <br /><button type="button" class="btn clear">Dismiss</button>', title: 'Error during load page');

            return view('livewire.show-dns-records', [
                'dnsRecords' => collect(),
            ]);
        }

        return view('livewire.show-dns-records', [
            'provider' => config('services.provider'),
            'dnsRecords' => $collection,
        ]);
    }
}
