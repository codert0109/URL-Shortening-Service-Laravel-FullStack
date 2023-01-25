<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithStubUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use DatabaseTransactions, WithStubUser;
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_index_authentication()
    {
        $this->assertAuthenticationRequired('/');
        $this->assertAuthenticationRequired('/urls');
    }
    public function test_index_view()
    {
        $user = $this->createStubUser();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('urls');
    }
    public function test_authenticated_user_can_create_new_url()
    {
        $this->actingAs($this->createStubUser());

        $this->post('/urls', ['destination' => 'https://test.test'])
            ->assertStatus(200);
    }
    public function test_it_checks_for_invalid_url()
    {
        $this->actingAs($this->createStubUser());

        $this->post('/urls', ['destination' => ''])
            ->assertStatus(422);
        $this->post('/urls', ['destination' => 'test'])
            ->assertStatus(422);
    }
}
