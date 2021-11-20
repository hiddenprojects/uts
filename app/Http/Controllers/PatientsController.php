<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Patients;
use App\Models\Status;

class PatientsController extends Controller {

    public function index() {

        $get_patients = Patients::count();

        if($get_patients > 0) {
            $return = [
                'code' => 200,
                'message' => 'The request succeeded',
                'data' => Patients::all()
            ];
        } else {
            $return = [
                'code' => 404,
                'message' => 'Resource not found',
                'data' => 'No data found'
            ];
        }

        return response()->json($return, $return['code']);

    }

    public function store(Request $request) {

        $get_status_id = Status::where('status', $request->status_id)->first();
        $get_patients_number = Patients::where('phone', $request->phone)->count();

        $store_data = $request->validate([
            'name' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
            'status_id' => 'required',
            'in_date_at' => 'required',
            'out_date_at' => ''
        ]);

        if($get_status_id != NULL) {
            $store_data['status_id'] = $get_status_id->id;
        }

        if($get_patients_number != NULL) {
            $return = [
                'code' => 200,
                'message' => 'No content to send',
                'data' => 'Phone number already exists'
            ];
        } else {
            $store_patients = Patients::create($store_data);
            $return = [
                'code' => 201,
                'message' => 'Resource created',
                'data' => $store_patients
            ];
        }

        return response()->json($return, $return['code']);

    }

    public function show($id) {
        $get_patients = Patients::where('id', $id)->first();

        if($get_patients) {
            $return = [
                'code' => 200,
                'message' => 'The request succeeded',
                'data' => $get_patients
            ];
        } else {
            $return = [
                'code' => 404,
                'message' => 'Resource not found',
                'data' => 'No data found'
            ];
        }

        return response()->json($return, $return['code']);

    }

    public function update(Request $request, $id) {

        $get_current_patients = Patients::where('id', $id)->first();
        $get_status_id = Status::where('status', $request->status_id)->first();

        if($get_current_patients) {
            $store_data = [
                'name' => $request->name??$get_current_patients->name,
                'phone' => $request->phone??$get_current_patients->phone,
                'address' => $request->address??$get_current_patients->address,
                'status_id' => $request->status_id??$get_current_patients->status_id,
                'in_date_at' => $request->in_date_at??$get_current_patients->in_date_at,
                'out_date_at' => $request->out_date_at??$get_current_patients->out_date_at,
            ];

            if($get_status_id != NULL) {
                $store_data['status_id'] = $get_status_id->id;
            }

            Patients::where('id', $id)->update($store_data);

            $return = [
                'code' => 201,
                'message' => 'Resource created',
                'data' => [
                    'after' => Patients::where('id', $id)->first(),
                    'before' => $get_current_patients
                ]
            ];
        } else {
            $return = [
                'code' => 404,
                'message' => 'Resource not found',
                'data' => 'No data found'
            ];
        }

        return response()->json($return, $return['code']);

    }

    public function destroy($id) {

        $get_patients = Patients::where('id', $id)->first();

        if($get_patients) {
            Patients::where('id', $id)->delete();
            $return = [
                'code' => 200,
                'message' => 'The request succeeded',
                'data' => 'Patient deleted'
            ];
        } else {
            $return = [
                'code' => 404,
                'message' => 'Resource not found',
                'data' => 'No data found'
            ];
        }

        return response()->json($return, $return['code']);

    }

    public function search($name) {

        $get_patients = Patients::where('name', 'LIKE', '%'.$name.'%')->get();

        if(count($get_patients) > 0) {
            $return = [
                'code' => 200,
                'message' => 'The request succeeded',
                'data' => $get_patients
            ];
        } else {
            $return = [
                'code' => 404,
                'message' => 'Resource not found',
                'data' => 'No data found'
            ];
        }

        return response()->json($return, $return['code']);

    }

    public function status($routes) {

        $get_resource = Status::all();
        foreach ($get_resource as $resource) {
            if(strtolower($routes) === strtolower($resource->status)) {
                $return = [
                    'code' => 200,
                    'message' => 'The request succeeded',
                    'data' => [
                        'status' => $resource->status,
                        'patients' => Patients::where('status_id', $resource->id)->get()
                    ]
                ];
                break;
            } else {
                $return = [
                    'code' => 404,
                    'message' => 'Resource not found',
                    'data' => 'No data found'
                ];
            }
        }

        return response()->json($return, $return['code']);

    }
}
