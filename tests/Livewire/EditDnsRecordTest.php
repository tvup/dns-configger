<?php

namespace Tests\Livewire;

use App\Livewire\EditDnsRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        Livewire::test(EditDnsRecord::class, ['id' => 1])
            ->assertSet('name', 'test')
            ->assertSet('type', 'A');
    }

    /** @test */
    public function it_updates_dns_record()
    {
        $updatedData = [
            'name' => 'Updated Name',
            'type' => 'A',
        ];

        Livewire::test(EditDnsRecord::class, ['id' => 1])
            ->set('name', $updatedData['name'])
            ->set('type', $updatedData['type'])
            ->call('update')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_shows_error_messages_for_invalid_data()
    {
        Livewire::test(EditDnsRecord::class, ['id' => 1])
            ->set('name', '') // empty name to trigger validation error
            ->call('update')
            ->assertHasErrors(['name' => 'required']);
    }
}
