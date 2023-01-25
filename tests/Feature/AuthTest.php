<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithStubUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use DatabaseTransactions, WithStubUser;
    public function test_index_authentication()
    {
        $this->assertAuthenticationRequired('/urls', 'post');
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

        $this->postJson('/urls', ['destination' => 'https://test.test'])
            ->assertStatus(200);
    }
}
