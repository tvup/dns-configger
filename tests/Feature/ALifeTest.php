<?php

namespace Tests\Feature;

use Tests\TestCase;

class ALifeTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/dns-records');

        $response->assertStatus(200);
    }

    /**
     * Redirect to a specific page instead of /.
     */
    public function test_the_application_redirects_to_main_page_on_root(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
