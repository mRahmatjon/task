<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\UsersController;
use App\Models\User;
use App\Models\Bookings;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users list test
     * @var user - Creating new users using factory
     * @var response - Getting response from index method
     * @return void
     */

    public function test_users_list()
    {
        $users = User::factory()->count(3)->create();
        $response = (new UsersController)->index();
        $this->assertNotEmpty($response);
    }

    /**
     * Store new user test
     * @var response - Post json request with parameters and get response with specific json structure
     * @return void
     */

    public function test_storing_user(){
        $response = $this->post('/api/store-user',[
            'name' => 'some name',
            'email' => 'email@mail.ru',
            'password' => '1234556789'
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'id',
            'name',
            'email'
        ]);
    }

    /**
     * Update user test
     * @var user - Creating new user using factory
     * @var response - Post json request with parameters and get response with specific json structure
     * @return void
     */

    public function testing_updating_user(){
        $user = User::factory()->create();

        $response = $this->postJson('/api/update-user',[
            'id' => $user->id,
            'name' => 'some new name',
            'email' => 'news@mail.ru',
            'password' => 'new password'
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'name',
            'email'
        ]);
    }

    /**
     * Delete user with booked car
     * @var user - Create new User
     * @param Booking - Create new booking with this user
     * @var cheking - Get booking with this user
     * @var response - Request delete user url and get specific status that request failed
     * @return void
     */

    public function testing_deleting_users_with_car_existanse(){
        $user = User::factory()->create();

        Bookings::factory()->create([
            'user_id' => $user->id
        ]);

        $checking  = Bookings::where('user_id', $user->id)->first();
        if(!blank($checking)){
            $response = $this->get("/api/delete-user/$user->id");
            $response->assertStatus(422);
        }
    }

    /**
     * Delete user 
     * @var user - Create new User
     * @param Booking - Create new booking with another user_id
     * @var cheking - Get booking with this user_id
     * @var response - Request delete user url and get specific status that request is completed
     * @return void
     */

    public function testing_deleting_users(){
        $user = User::factory()->create([
            'id' => 34
        ]);

        Bookings::factory()->create([
            'user_id' => 23
        ]);

        $checking  = Bookings::where('user_id', $user->id)->first();
        if(blank($checking)){
            $response = $this->get("/api/delete-user/$user->id");
            $response->assertStatus(200);
        }
    }
}
