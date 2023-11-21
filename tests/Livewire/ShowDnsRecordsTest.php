<?php

namespace Tests\Livewire;

use App\Livewire\ShowDnsRecords;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShowDnsRecordsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        Livewire::test(ShowDnsRecords::class)
            ->assertStatus(200);
    }

    /** @test */
    public function it_can_delete_a_dns_record()
    {
        Livewire::test(ShowDnsRecords::class)
            ->call('delete', 1)
            ->assertStatus(200);
    }

    /** @test */
    public function it_shows_error_message_when_delete_fails()
    {
        Livewire::test(ShowDnsRecords::class)
            ->call('delete', '999')
            ->assertSee('Error');
    }

    /** @test */
    public function it_can_redirect_to_edit_page()
    {
        Livewire::test(ShowDnsRecords::class)
            ->call('edit', 1)
            ->assertRedirect('/dns-records/edit/' . 1);
    }
}
