<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DnsRecord;


class CreateDnsRecord extends Component
{

    public $type = '';
    public $data = '';

    public function save()
    {
        $this->validate([
           'type' => 'required',
            'data' => 'required',
        ]);

        $model = new DnsRecord();
        $model->type = $this->type;
        $model->data = $this->data;
        $model->create();
        $this->redirect('/dns-records');
    }

    public function render()
    {
        return view('livewire.create-dns-record');
    }
}
