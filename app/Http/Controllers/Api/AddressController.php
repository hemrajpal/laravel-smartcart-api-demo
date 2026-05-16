<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Helpers\ApiResponse;

class AddressController extends Controller
{
    public function list(Request $request)
    {
        $addresses = \App\Models\Address::where('user_id', Auth::id())->get();
        return ApiResponse::success($addresses);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address_line_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'postal_code' => 'required|int',
            'is_default' => 'required|int',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', $validator->errors(), 422);
        }

        if($request->is_default == 1){
            \App\Models\Address::where('user_id', Auth::id())->where('is_default', 1)->update(['is_default' => 0]);
        }

        $address = \App\Models\Address::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'is_default' => $request->is_default,
        ]);

        return ApiResponse::success($address, 'Address has been added');
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address_line_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'postal_code' => 'required|int',
            'is_default' => 'required|int',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', $validator->errors(), 422);
        }

        $address = \App\Models\Address::where('user_id', Auth::id())->where('id', $id)->first();
        if(!$address){
            return ApiResponse::error('Address not found', $validator->errors(), 404);
        }

        if($request->is_default == 1){
            \App\Models\Address::where('user_id', Auth::id())->where('is_default', 1)->update(['is_default' => 0]);
        }

        $address->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'is_default' => $request->is_default,
        ]);

        return ApiResponse::success($address, 'Address has been updated');
    }

    public function delete($id)
    {
        $userId = Auth::id();

        $address = \App\Models\Address::where('user_id', $userId)->where('id', $id)->first();
        if(!$address){
            return ApiResponse::error('Address not found', [], 404);
        }

        $address->delete();

        return ApiResponse::success([], 'Address has been deleted');
    }
}
