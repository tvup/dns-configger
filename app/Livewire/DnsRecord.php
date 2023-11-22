<?php

namespace App\Livewire;

use Livewire\Component;

class DnsRecord extends Component
{
    public function render(): \Illuminate\View\View
    {
        return view('livewire.dns-record');
    }
}
