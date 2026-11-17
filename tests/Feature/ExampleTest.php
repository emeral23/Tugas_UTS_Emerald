<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_dashboard_is_the_successful_home_page(): void
    {
        $response = $this->get('/');

        $response->assertOk()->assertSee('Dashboard');
    }
}
