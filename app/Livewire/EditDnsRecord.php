<?php

namespace App\Livewire;

use App\Models\DnsRecord;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Edit DNS record')]
class EditDnsRecord extends Component
{
    public int $id;

    #[Validate('required|in:A,AAAA,CAA,CNAME,MX,NS,SRV,TXT,SOA', message: ['type.in'=>'Type must be one of A, AAAA, CAA, CNAME, MX, NS, SRV, TXT, SOA'])]
    public string $type = '';

    #[Validate('required_unless:type,SOA')]
    public ?string $name = null;

    #[Validate('required_unless:type,SOA')]
    public string $data = '';

    #[Validate('required_if:type,MX|required_if:type,SRV|nullable|integer')]
    public ?int $priority = null;

    #[Validate('required_if:type,SRV|nullable|integer')]
    public ?int $port = null;

    #[Validate('required|integer')]
    public int $ttl = 600;

    #[Validate('required_if:type,SRV|nullable|integer')]
    public ?int $weight = null;

    #[Validate('required_if:type,CAA|nullable|integer|min:0|max:255')]
    public ?int $flags = null;

    #[Validate('required_if:type,CAA|nullable|in:issue,issuewild,iodef', message: ['tag.in'=>'Tag must be one of issue, issuewild, iodef'])]
    public string|null $tag = null;

    public function update() : void
    {
        $this->validate();

        $validator = Validator::make(
            [
                'type' => $this->type,
                'data' => $this->data,
            ],
            [
                'type' => 'required|in:A,AAAA,CAA,CNAME,MX,NS,SRV,TXT,SOA',
                'data' => 'required_unless:type,SOA',
            ]
        );

        $validator->sometimes('data', 'ipv4', function (Fluent $input) {
            return $input->type == 'A';
        });

        $validator->sometimes('data', 'ipv6', function (Fluent $input) {
            return $input->type == 'AAAA';
        })->validate();

        /** @var DnsRecord $model */ //$model cannot become null. If dnsRecord doesn't exist, an exception is thrown
        $model = DnsRecord::find($this->id);
        if ($model->type != $this->type) {
            $this->dispatch('error', type: 'error', message: 'Type cannot be changed. <br /><button type="button" class="btn clear">Dismiss</button>', title: 'Error during update');

            return;
        }
        $model->name = $this->name;
        $model->data = $this->data;
        $model->priority = $this->priority;
        $model->port = $this->port;
        $model->ttl = $this->ttl;
        $model->weight = $this->weight;
        $model->flags = $this->flags;
        $model->tag = $this->tag;
        try {
            $model->save();
        } catch (\Exception $e) {
            $this->dispatch('error', type: 'error', message: 'Error: ' . $e->getMessage() . ' <br /><button type="button" class="btn clear">Dismiss</button>', title: 'Error during save');

            return;
        }
        $this->dispatch('success', type: 'success', message: 'Record Updated Successfully.');
        $this->name = $model->name;
        $this->data = $model->data;
        $this->priority = $model->priority;
        $this->port = $model->port;
        $this->ttl = $model->ttl;
        $this->weight = $model->weight;
        $this->flags = $model->flags;
        $this->tag = $model->tag;
    }

    public function mount(int $id) : void
    {
        $this->id = $id;
        try {
            /** @var DnsRecord $model */
            $model = DnsRecord::find($this->id);
        } catch (\Exception $e) {
            $this->dispatch('error', type: 'error', message: 'Error: ' . $e->getMessage() . ' <br /><button type="button" class="btn clear">Dismiss</button>', title: 'Error during load page');

            return;
        }
        $this->type = $model->type;
        $this->name = $model->name;
        $this->data = $model->data;
        $this->priority = $model->priority;
        $this->port = $model->port;
        $this->ttl = $model->ttl;
        $this->weight = $model->weight;
        $this->flags = $model->flags;
        $this->tag = $model->tag;
    }

    public function rendering() : void
    {
        if (session()->has('message')) {
            $this->dispatch('success', type: 'success', message: session()->get('message'));
        }
    }

    public function cancel() : \Illuminate\Http\RedirectResponse|\Livewire\Features\SupportRedirects\Redirector
    {
        return redirect('/dns-records');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.edit-dns-record');
    }
}
