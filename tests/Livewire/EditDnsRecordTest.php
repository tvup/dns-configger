<?php

namespace Tests\Livewire;

use App\Livewire\EditDnsRecord;
use App\Livewire\ShowDnsRecords;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class EditDnsRecordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        Livewire::test(EditDnsRecord::class, ['id' => 1])
            ->assertStatus(200);
    }

    /** @test */
    public function component_mounts_with_dns_record_data()
    {
        $dnsRecordsFile = Storage::disk('test')->get('dns-records.json');
        $arrayAway = json_decode($dnsRecordsFile, true);
        $array = [];
        foreach ($arrayAway as $thing) {
            $dnsRecord = new \App\Models\DnsRecord();
            $dnsRecord->forceFill($thing);
            $array[] = $dnsRecord;
        }
        $collection = collect($array);
        $dnsRecord = $collection->first();

        $test = Livewire::test(EditDnsRecord::class, ['id' => $dnsRecord->id]);
        $this->assertEquals($dnsRecord->id, $test->getData()['id']);
    }

    /** @test */
    public function it_updates_dns_record()
    {
        $test = Livewire::test(ShowDnsRecords::class);
        $collection = $test->viewData('dnsRecords');
        $dnsRecord = $collection->first();

        $updatedData = [
            'ttl' => 123,
        ];

        $tets = Livewire::test(EditDnsRecord::class, ['id' => $dnsRecord->id])
            ->set('ttl', $updatedData['ttl'])
            ->call('update');
        $tets->assertSee('Post Updated Successfully.');
        $tets->assertHasNoErrors();
    }

    /** @test */
    public function it_shows_error_messages_for_invalid_data()
    {
        Livewire::test(EditDnsRecord::class, ['id' => 1])
            ->set('name', '') // empty name to trigger validation error
            ->call('update')
            ->assertHasErrors(['name' => 'required_unless']);
    }
}
