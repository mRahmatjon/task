<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\BookingController;
use App\Models\Cars;
use App\Models\User;
use App\Models\Bookings;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 
     * Booking list test
     * @param bookings - Creating new booking using factory
     * @var response - Getting response from index method
     * @return void
     */

    public function test_booking_list(){
        Bookings::factory()->count(3)->create();
        $response = (new BookingController)->index();
        $this->assertNotEmpty($response);
    }

    /**
     * Store new booking test
     * @var response - Post json request with parameters and get response with specific json structure
     * @return void
     */

    public function test_storing_booking(){
        User::factory()->create();
        Cars::factory()->create();

        $response = $this->post('/api/store-booking',[
            'user_id' => User::first()->id,
            'car_id' => Cars::first()->id
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'user',
            'car'
        ]);
    }

    /**
     * Test booking car that already has user
     * @param User - Creating users using factory
     * @param Cars - Creating car using factory
     * @var booking - Create booking
     * @var response - Post json request with parameters and get response with specific status
     */

    public function test_booking_car_on_couple_users(){
        User::factory()->count(2)->create();
        Cars::factory()->create();

        $booking = Bookings::factory()->create([
            'user_id' => User::first()->id,
            'car_id' => Cars::first()->id
        ]);

        $response = $this->post('/api/store-booking',[
            'user_id' => User::orderBy('id', 'desc')->first()->id,
            'car_id' => Cars::first()->id
        ]);

        $response->assertStatus(422);
    }

     /**
     * Test booking user that already has car
     * @param User - Creating user using factory
     * @param Cars - Creating cars using factory
     * @var booking - Create booking
     * @var response - Post json request with parameters and get response with specific status
     */

    public function test_booking_user_on_couple_cars(){
        User::factory()->create();
        Cars::factory()->count(2)->create();

        $booking = Bookings::factory()->create([
            'user_id' => User::first()->id,
            'car_id' => Cars::first()->id
        ]);

        $response = $this->post('/api/store-booking',[
            'user_id' => User::first()->id,
            'car_id' => Cars::orderBy('id', 'desc')->first()->id
        ]);

        $response->assertStatus(422);
    }

     /**
     * Update booking test
     * @var car - Creating new booking using factory
     * @var response - Post json request with parameters and get response with specific json structure
     * @return void
     */

    public function testing_updating_booking(){
        $booking = Bookings::factory()->create([
            'id' => 11
        ]);

        User::factory()->create();
        Cars::factory()->create();

        $response = $this->postJson('/api/update-booking',[
            'id' => 11,
            'user_id' => User::first()->id,
            'car_id' => Cars::first()->id
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'user',
            'car'
        ]);
    }

    /**
     * Delete booking
     * @var booking - Create new booking
     * @var response - Request delete car url and get specific status that request is completed
     * @return void
     */

    public function testing_deleting_booking(){
        $booking = Bookings::factory()->create([
            'id' => 34
        ]);

        $response = $this->get("/api/delete-booking/$booking->id");
        $response->assertStatus(200);
    }
}
