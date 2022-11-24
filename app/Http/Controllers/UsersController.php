<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// Models
use App\Models\User;
use App\Models\Bookings;
// Request
use App\Http\Requests\UsersRequest;

class UsersController extends Controller
{

    /**
     * Getting data from the database
     * @var data
     * @param Users - Model
     * @return json
     */

    public function index(){
        $data = User::get();
        return json_encode($data);
    }

    /**
     * Writing data to the database
     * @param UsersRequest - request validation
     * @var data
     * @param User - model
     * @return response
     */

    public function store(UsersRequest $request){
        $data = User::create($request->validated());
        return response()->json([
            'id' => $data->id,
            'name' => $data->name,
            'email' => $data->email,
        ]);
    }

    /**
     * Updating data in database
     * @param UsersRequest - request validation
     * @var data
     * @param User - model
     * @return response
     */

    public function update(UsersRequest $request){
        $data = User::find($request->id)->update($request->validated());
        return response()->json([
            'name' => $request->name,
            'email' => $request->email,
        ]);
    }

    /**
     * Delete data from database
     * @param id - ID of data
     * @param checkBooking - bookings model for checking
     * @return bool 
     */

    public function delete($id){
        // Search for the same id in booking table
        $checkBooking = Bookings::where('user_id', $id)->get();

        // If variable is not blank return warning
        if(!blank($checkBooking)){
            return response('У данного пользователя забронирована машина! Удалите бронь для того чтобы продолжить', 422);
        }else{
            // If varialble is blank then delete data
            User::find($id)->delete();
            return true;
        }
    }
}
