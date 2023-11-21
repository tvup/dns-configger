<?php

namespace App\Livewire;

use App\Models\DnsRecord;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditDnsRecord extends Component
{
    public $id = '';

    #[Validate('required|in:A,AAAA,CAA,CNAME,MX,NS,SOA,SRV,TXT', message: ['type.in'=>'Type must be one of A, AAAA, CAA, CNAME, MX, NS, SOA, SRV, TXT'])]
    public $type = '';

    #[Validate('required')]
    public $name = '';

    #[Validate('required')]
    public $data = '';

    #[Validate('nullable|integer')]
    public $priority = '';

    #[Validate('nullable|integer')]
    public $port;

    #[Validate('nullable|integer')]
    public $ttl;

    #[Validate('nullable|integer')]
    public $weight;

    #[Validate('nullable|integer|min:0|max:255')]
    public $flags;

    #[Validate('nullable|in:issue,issuewild,iodef', message: ['tag.in'=>'Tag must be one of issue, issuewild, iodef'])]
    public $tag;

    public function update()
    {
        $this->validate();
        $model = DnsRecord::find($this->id);
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
        session()->flash('message', 'Post Updated Successfully.');
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

    public function render()
    {
        return view('livewire.edit-dns-record');
    }
}
