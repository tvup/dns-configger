<?php

namespace Tests\Livewire;

use App\Livewire\ShowDnsRecords;
use Illuminate\Http\JsonResponse;
use Livewire\Livewire;
use Tests\TestCase;

class ShowDnsRecordsTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        Livewire::test(ShowDnsRecords::class)
            ->assertStatus(200);
    }

    /** @test */
    public function component_exists_on_the_page()
    {
        $this->get('/dns-records')
            ->assertSeeLivewire(ShowDnsRecords::class);
    }

    /** @test */
    public function random_dns_records_are_displayed_on_the_page()
    {
        Livewire::test(ShowDnsRecords::class)
            ->assertViewHas('dnsRecords', function ($dnsRecords) {
                $this->assertGreaterThanOrEqual(1, count($dnsRecords));

                return true;
            });
    }

    /** @test */
    public function it_can_delete_a_dns_record()
    {
        $test = Livewire::test(ShowDnsRecords::class);
        $dnsRecords = $test->viewData('dnsRecords');

        //Get one for deletion
        $dnsRecord = $dnsRecords->first();

        $test2 = $test->call('delete', $dnsRecord->id);
        $dnsRecords = $test2->viewData('dnsRecords');
        $this->assertEquals(1, $dnsRecords->where('id', $dnsRecord->id)->count());
        $test2->assertStatus(200);
    }

    /** @test */
    public function it_shows_error_message_when_delete_fails()
    {
        Livewire::test(ShowDnsRecords::class)
            ->call('delete', '999')
            ->assertDispatched('error', function ($event) {
                $this->assertEquals($event, 'error');

                return true;
            });
    }

    /** @test */
    public function it_can_redirect_to_edit_page_even_no_record_is_found()
    {
        /** @var JsonResponse $test */
        $test = Livewire::test(ShowDnsRecords::class)
            ->call('edit', 1);
        $test->assertRedirect('/dns-records/edit/' . 1);

        $response = $this->get('/dns-records/edit/' . 1);
        $response->assertStatus(200);
        $response->assertSee('Error: Resource not found');
    }

    /** @test */
    public function it_can_redirect_to_edit_page_and_get_data()
    {
        /** @var JsonResponse $test */
        $test = Livewire::test(ShowDnsRecords::class);
        $collection = $test->viewData('dnsRecords');
        $dnsRecord = $collection->first();
        $test->call('edit', $dnsRecord->id);
        $test->assertRedirect('/dns-records/edit/' . $dnsRecord->id);

        $response = $this->get('/dns-records/edit/' . $dnsRecord->id);
        $response->assertStatus(200);
        $response->assertSee($dnsRecord->id);
    }
}
