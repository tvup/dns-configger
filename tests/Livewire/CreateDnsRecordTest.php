<?php

namespace Tests\Livewire;

use App\Livewire\CreateDnsRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateDnsRecordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        Livewire::test(CreateDnsRecord::class)
            ->assertStatus(200);
    }

    /** @test */
    public function it_creates_a_new_dns_record()
    {
        $dnsRecordData = [
            'type' => 'A',
            'name' => 'example.com',
            'data' => '192.0.2.1',
        ];

        $test = Livewire::test(CreateDnsRecord::class)
            ->set('type', $dnsRecordData['type'])
            ->set('name', $dnsRecordData['name'])
            ->set('data', $dnsRecordData['data'])
            ->call('save');
        $newId = $test->get('id');
        $test->assertHasNoErrors();
        $test->assertRedirect('/dns-records/edit/' . $newId);
        $response = $this->get('/dns-records/edit/' . $newId);
        $response->assertStatus(200);
        $response->assertSee($newId);
        $response->assertSee('example.com');
        $response->assertSee('192.0.2.1');
    }

    /** @test */
    public function it_shows_error_messages_for_invalid_data()
    {
        Livewire::test(CreateDnsRecord::class)
            ->set('name', '') // empty name to trigger validation error
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }
}
