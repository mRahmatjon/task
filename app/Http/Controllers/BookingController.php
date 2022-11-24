<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
// Models
use App\Models\Bookings;
// Requests
use App\Http\Requests\BookingsRequest;

class BookingController extends Controller
{

    /**
     * Getting data from the database
     * @var data
     * @param Bookings - Model
     * @return json
     */

    public function index(){
        $data = Bookings::get();
        return json_encode($data);
    }

    /**
     * Writing data to the database
     * @param BookingsRequest - request validation
     * @var data
     * @param Bookings - model
     * @return response
     * 
     */

    public function store(BookingsRequest $request){
        // Passing request for check
        $checking = $this->checkOnExistance($request);
        // If user or car are not booked
        if($checking == FALSE){
            $data = Bookings::create($request->validated());
            return response()->json([
                'user' => $data->user_id,
                'car' => $data->car_id
            ]);
        }else{
            // If user or car are booked
            return response("Операция не возможна! $checking", 422);
        }
    }

    /**
     * Updating data to the database
     * @param BookingsRequest - request validation
     * @var data
     * @param Bookings - model
     * @return response
     * 
     */

    public function update(BookingsRequest $request){
        // Passing request for check
        $checking = $this->checkOnExistance($request);
        // If user or car are not booked
        if($checking == FALSE){
            $data = Bookings::find($request->id)->update($request->validated());
            return response()->json([
            'user' => $request->user_id,
            'car' => $request->car_id, 
            ]);
        }else{
            // If user or car are booked
            return response("Операция не возможна! $checking", 422);
        }
    }

    /**
     *  Delete data from database
     * @param id - ID of data
     * @param Bookings - model
     * @return bool 
     */

    public function delete($id){
        Bookings::find($id)->delete();
        return true;
    }

    /**
     * Function for cheking is user or car is booked
     * @param request
     * @return
     */

    private function checkOnExistance($request){
        // Checking user on booking existabce
        $user = Bookings::where('user_id', $request->user_id)->get();
        if(!blank($user)){
            return "Для данного пользователя уже привязана машина!";
        }
        // Checking car on booking existance
        $car = Bookings::where('car_id', $request->car_id)->get();
        if(!blank($car)){
            return "Для данной машины уже привязан пользователь!";
        }
        // If result not found
        return false;
    }
}
