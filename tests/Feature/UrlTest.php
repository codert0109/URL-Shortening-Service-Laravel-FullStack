<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\WithStubUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends TestCase
{
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
    public function test_it_checks_for_invalid_url()
    {
        $this->actingAs($this->createStubUser());

        // $this->post('/urls', ['destination' => ''])
        //     ->assertStatus(422);
        $this->postJson('/urls', ['destination' => 'test'])
            ->assertStatus(422);
    }
}
