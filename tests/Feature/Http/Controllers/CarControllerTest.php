<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class CarControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();       
    }

    

    /** 
     * @test guest
    */
    public function guest_cannot_ping()
    {
        $this->json('get', 'api/car')->assertStatus(401);
    }

    /**
     * Test index.
     *
     * @return void
     */
    public function test_index()
    {
        Sanctum::actingAs(
            $this->user
        );
     
        $response = $this->get('/api/car');

        $response->assertStatus(200);
    }

    /**
     * Test create.
     *
     * @return void
     */
    public function test_create()
    {
        
        Sanctum::actingAs($this->user);
     
        $response = $this->post('/api/car', [
            'model' => 'test_name_model',
        ]);

        $this->user->refresh();

        $response->assertStatus(200);
        $this->assertTrue($this->user->car->model == $response['data']['model']);

        // Тест повторного добавления
        $response = $this->post('/api/car', [
            'model' => 'test_name_model2',
        ]);
        $response->assertStatus(400);

    }

    /**
     * Test view.
     *
     * @return void
     */
    public function test_view()
    {
        $this->test_create();

        $this->user->refresh();

        $response = $this->get('/api/car/' . $this->user->car->getKey());
        $response->assertStatus(200);
        $this->assertTrue($this->user->car->model == $response['data']['model']);
    }

    /**
     * Test my.
     *
     * @return void
     */
    public function test_my()
    {
        $this->test_create();

        $this->user->refresh();

        $response = $this->get('/api/car/my');
        $response->assertStatus(200);
        $this->assertTrue($this->user->car->model == $response['data']['model']);
    }

    /**
     * Test update.
     *
     * @return void
     */
    public function test_update()
    {
        $this->test_create();

        $response = $this->put('/api/car/' . $this->user->car->getKey(), [
            'model' => 'test_name_model3',
        ]);

        $this->user->refresh();

        $response->assertStatus(200);
        $this->assertTrue($this->user->car->model == $response['data']['model']);
    }


    /**
     * Test delete.
     *
     * @return void
     */
    public function test_delete()
    {
        $this->test_create();

        $response = $this->delete('/api/car/' . $this->user->car->getKey());

        $this->user->refresh();

        $response->assertStatus(200);
        $this->assertTrue($this->user->car == null);
    }

}
