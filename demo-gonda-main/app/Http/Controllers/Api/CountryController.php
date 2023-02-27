<?php

namespace App\Http\Controllers\Api;

use App\Model\City;
use App\Http\Controllers\Controller;
use App\Model\Country;
use App\Model\GlobalSettingManagement;
use App\Model\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    public function country(Request $request){
        $country_name = Country::get(['id', 'name']);
        if ($country_name->isNotEmpty()){
            return response()->json([
                'status' => 1,
                'message' => 'All Country',
                'data' => $country_name
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'No Records',
            'data' => []
        ]);
    }

    public function state(Request $request){
        $validator = Validator::make($request->all(), [
            'country_id' => 'required|exists:countries,id',
        ],[
            'country_id.exists' => 'The entered country id does not exists.',
        ]);
        if ($validator->fails()){
            $error = $validator->errors()->first();
            return response()->json([
                "success" => 0,
                "message" => $error,
            ]);
        }
        $country_id = $request['country_id'];

        $state = State::where('country_id', $country_id)->get();
        $state_data = [];
        if ($state->isNotEmpty()){
            foreach ($state as $data){
                $state_data [] = [
                    'state_id' => $data->id,
                    'state_name' => $data->name,
                ];
            }
            return response()->json([
                'status' => 1,
                'message' => 'State data',
                'data' => $state_data
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'No Records',
            'data' => []
        ]);
    }

    public function city(Request $request){
        $validator = Validator::make($request->all(), [
            'state_id' => 'required|exists:states,id',
        ],[
            'state_id.exists' => 'The entered state id does not exists.',
        ]);
        if ($validator->fails()){
            $error = $validator->errors()->all();
            return response()->json([
                "success" => 0,
                "message" => $error,
            ]);
        }
        $city = City::where('state_id', $request->state_id)->get();
        $city_data = [];
        if ($city->isNotEmpty()){
            foreach ($city as $data){
                $city_data [] = [
                    'city_id' => $data->id,
                    'city_name' => $data->name,
                ];
            }
            return response()->json([
                'status' => 1,
                'message' => 'City Data',
                'dat0a' => $city_data
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'No Records',
            'data' => []
        ]);

    }

    public function stateUs(Request $request){
        $state = State::where('country_id', 231)->get();
        $global_setting = GlobalSettingManagement::first();
        $global_data = [];
        if (!empty($global_setting)){
            $global_data = $global_setting->content;
        }
        $state_data = [];
        if ($state->isNotEmpty()){
            foreach ($state as $data){
                $state_data [] = [
                    'id' => $data->id,
                    'name' => $data->name,
                ];
            }
            return response()->json([
                'status' => 1,
                'message' => 'US State',
                'global_data' => $global_data,
                'data' => $state_data
            ]);
        }
        return response()->json([
            'status' => 1,
            'message' => 'No Records',
            'data' => []
        ]);
    }
}
