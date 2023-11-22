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
    public $id = '';

    #[Validate('required|in:A,AAAA,CAA,CNAME,MX,NS,SRV,TXT', message: ['type.in'=>'Type must be one of A, AAAA, CAA, CNAME, MX, NS, SRV, TXT'])]
    public $type = '';

    #[Validate('required')]
    public $name = '';

    #[Validate('required')]
    public $data = '';

    #[Validate('required_if:type,MX|required_if:type,SRV|nullable|integer')]
    public $priority = '';

    #[Validate('required_if:type,SRV|nullable|integer')]
    public $port;

    #[Validate('nullable|integer')]
    public $ttl;

    #[Validate('required_if:type,SRV|nullable|integer')]
    public $weight;

    #[Validate('required_if:type,CAA|nullable|integer|min:0|max:255')]
    public $flags;

    #[Validate('required_if:type,CAA|nullable|in:issue,issuewild,iodef', message: ['tag.in'=>'Tag must be one of issue, issuewild, iodef'])]
    public $tag;

    public function save()
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

    public function cancel()
    {
        return redirect('/dns-records');
    }

    public function render()
    {
        return view('livewire.create-dns-record');
    }
}
