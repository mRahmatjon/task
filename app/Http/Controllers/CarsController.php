<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Models
use App\Models\Cars;
use App\Models\Bookings;
// Requests
use App\Http\Requests\CarsRequest;

class CarsController extends Controller
{
    /**
     * Getting data from the database
     * @var data
     * @param Cars - Model
     * @return json
     */

    public function index(){
        $data = Cars::get();
        return json_encode($data);
    }

    /**
     * Writing new data to the database
     * @param CarsCreateRequest - request validation
     * @var data
     * @param Cars - model
     * @return response
     */

    public function store(CarsRequest $request){
        $data = Cars::create($request->validated());
        return response()->json([
            'id' => $data->id,
            'model' => $data->model
        ]);
    }

    /**
     * Updating data in database
     * @param CarsCreateRequest - request validation
     * @var data
     * @param Cars - model
     * @return response
     */

    public function update(CarsRequest $request){
        $data = Cars::find($request->id)->update($request->validated());
        return response()->json([
            'model' => $request->model
        ]);
    }

    /**
     * Delete data form database
     * @param id - ID of data
     * @param checkBooking - bookings model for checking
     * @return bool
     */
    
    public function delete($id){
        // Search for the same id in booking table
        $checkBooking = Bookings::where('car_id', $id)->get();

        // If variable is not blank return warning
        if(!blank($checkBooking)){
            return response('Для данной машины забронирован пользователь! Удалите бронь для того чтобы продолжить', 422);
        }else{
            // If varialble is blank then delete data
            Cars::find($id)->delete();
            return true;
        }
    }
}
