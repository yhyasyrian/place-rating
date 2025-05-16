<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_login_than_go_dashboard(): void
    {
        $this->actingAs(User::factory()->unverified()->create());
        $this
        ->get('/dashboard')
        ->assertStatus(302);
    }
}
