<?php

namespace App\Livewire;

use App\Models\BaseDnsRecord;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Create DNS record')]
class CreateDnsRecord extends Component
{
    public int|string $id;

    private ?BaseDnsRecord $dnsRecord = null;

    #[Validate('required|in:A,AAAA,CAA,CNAME,MX,NS,SRV,TXT', message: ['type.in'=>'Type must be one of A, AAAA, CAA, CNAME, MX, NS, SRV, TXT'])]
    public string $type = '';

    #[Validate('required')]
    public ?string $name = null;

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

    private function getDnsRecord(): BaseDnsRecord
    {
        if (!$this->dnsRecord) {
            $this->dnsRecord = App::make(BaseDnsRecord::class);
        }

        return $this->dnsRecord;
    }

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

        $model = $this->getDnsRecord();
        assert(null !== $this->name);
        $model->setAttributes([
            'type' => $this->type,
            'name' => $this->name,
            'data' => $this->data,
            'ttl' => $this->ttl,
        ]);

        try {
            $model->save();
        } catch (\Exception $e) {
            $this->dispatch('error', type: 'error', message: 'Error: ' . $e->getMessage() . ' <br /><button type="button" class="btn clear">Dismiss</button>', title: 'Error during save');

            return;
        }
        $this->id = $model->id;
        session()->flash('message', 'DNS record created successfully.');
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
