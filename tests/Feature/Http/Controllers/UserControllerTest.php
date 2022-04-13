<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public $userParams;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userParams = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(8),
        ];    
    }

    /**
     * Test register.
     *
     * @return void
     */
    public function test_register()
    {
        $response = $this->post('/api/user/register', $this->userParams);

        $response->assertStatus(200);
        $this->assertTrue(isset($response['data']['token']));

        // Тест не правильных данных
        $response = $this->post('/api/user/register', []);
        $response->assertStatus(401);
    }

    /**
     * Test get token.
     *
     * @return void
     */
    public function test_token()
    {
        
        $this->test_register();
     
        $response = $this->post('/api/user/token', $this->userParams);

        $response->assertStatus(200);
        $this->assertTrue(isset($response['data']['token']));

        // Тест не правильных данных
        $response = $this->post('/api/user/token', []);
        $response->assertStatus(401);

    }


}
