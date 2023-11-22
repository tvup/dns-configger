<?php

namespace App\Livewire;

use App\Models\DnsRecord;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Create DNS record')]
class CreateDnsRecord extends Component
{
    public int $id;

    #[Validate('required|in:A,AAAA,CAA,CNAME,MX,NS,SRV,TXT', message: ['type.in'=>'Type must be one of A, AAAA, CAA, CNAME, MX, NS, SRV, TXT'])]
    public string $type = '';

    #[Validate('required')]
    public string $name = '';

    #[Validate('required')]
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

    public function save(): void
    {
        $this->validate();

        $validator = Validator::make(
            [
                'type' => $this->type,
                'data' => $this->data,
            ],
            [
                'type' => 'required|in:A,AAAA,CAA,CNAME,MX,NS,SRV,TXT',
                'data' => 'required',
            ]
        );

        $validator->sometimes('data', 'ipv4', function (Fluent $input) {
            return $input->type == 'A';
        });

        $validator->sometimes('data', 'ipv6', function (Fluent $input) {
            return $input->type == 'AAAA';
        })->validate();

        $model = new DnsRecord();
        $model->type = $this->type;
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
            session()->flash('message', 'Error: ' . $e->getMessage());

            return;
        }
        $this->id = $model->id;
        $this->redirect('/dns-records/edit/' . $model->id);
    }

    public function cancel():   \Illuminate\Http\RedirectResponse
    {
        return redirect('/dns-records');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.create-dns-record');
    }
}
