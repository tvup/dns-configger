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
    public $id = '';

    #[Validate('required|in:A,AAAA,CAA,CNAME,MX,NS,SRV,TXT,SOA', message: ['type.in'=>'Type must be one of A, AAAA, CAA, CNAME, MX, NS, SRV, TXT, SOA'])]
    public $type = '';

    #[Validate('required_unless:type,SOA')]
    public $name = '';

    #[Validate('required_unless:type,SOA')]
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

    public function update()
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

        $model = DnsRecord::find($this->id);
        if ($model->type != $this->type) {
            session()->flash('message', 'Error: ' . 'Type cannot be changed.');

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
            session()->flash('message', 'Error: ' . $e->getMessage());

            return;
        }
        session()->flash('message', 'Post Updated Successfully.');
        $this->name = $model->name;
        $this->data = $model->data;
        $this->priority = $model->priority;
        $this->port = $model->port;
        $this->ttl = $model->ttl;
        $this->weight = $model->weight;
        $this->flags = $model->flags;
        $this->tag = $model->tag;
    }

    public function mount($id)
    {
        $this->id = $id;
        try {
            $model = DnsRecord::find($this->id);
        } catch (\Exception $e) {
            session()->flash('message', 'Error: ' . $e->getMessage());

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

    public function cancel()
    {
        return redirect('/dns-records');
    }

    public function render()
    {
        return view('livewire.edit-dns-record');
    }
}
