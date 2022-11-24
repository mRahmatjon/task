<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\CarsController;
use App\Models\Cars;
use App\Models\Bookings;

class CarsTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Cars list test
     * @var car - Creating new cars using factory
     * @var response - Getting response from index method
     * @return void
     */

    public function test_cars_list()
    {
        $cars = Cars::factory()->count(3)->create();
        $response = (new CarsController)->index();
        $this->assertNotEmpty($response);
    }

    /**
     * Store new car test
     * @var response - Post json request with parameters and get response with specific json structure
     * @return void
     */

    public function test_storing_car(){
        $response = $this->post('/api/store-car',[
            'model' => 'model'
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'id',
            'model'
        ]);
    }

    /**
     * Update car test
     * @var car - Creating new car using factory
     * @var response - Post json request with parameters and get response with specific json structure
     * @return void
     */

    public function testing_updating_car(){
        $cars = Cars::factory()->count(1)->create([
            'id' => 10
        ]);

        $response = $this->postJson('/api/update-car',[
            'id' => 10,
            'model' => 'some new model'
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'model',
        ]);
    }

    /**
     * Delete car with booked user
     * @var car - Create new Uscarer
     * @param Booking - Create new booking with this car
     * @var cheking - Get booking with this car
     * @var response - Request delete uscarer url and get specific status that request failed
     * @return void
     */

    public function testing_deleting_car_with_user_existanse(){
        $car = Cars::factory()->create();

        Bookings::factory()->create([
            'car_id' => $car->id
        ]);

        $checking  = Bookings::where('car_id', $car->id)->first();
        if(!blank($checking)){
            $response = $this->get("/api/delete-car/$car->id");
            $response->assertStatus(422);
        }
    }

    /**
     * Delete car
     * @var user - Create new car
     * @param Booking - Create new booking with another car_id
     * @var cheking - Get booking with this car_id
     * @var response - Request delete car url and get specific status that request is completed
     * @return void
     */

    public function testing_deleting_car(){
        $car = Cars::factory()->create([
            'id' => 34
        ]);

        Bookings::factory()->create([
            'car_id' => 23
        ]);

        $checking  = Bookings::where('car_id', $car->id)->first();
        if(blank($checking)){
            $response = $this->get("/api/delete-car/$car->id");
            $response->assertStatus(200);
        }
    }
}
