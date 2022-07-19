<?php

namespace App\Http\Controllers\AdminPanel\Service;

use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\Service;

class ServiceCategoryController extends Controller
{
    public function addServiceCategory(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $fields = $request->validate([
            'serviceCategoryName' => ['required', 'string'],
        ]);

        $serviceCategory = ServiceCategory::create([
            'serviceCategoryName' => $fields['serviceCategoryName']
        ]);

        return [
            'message'=> 'Service category created successfully',
        ];
    }

    public function getServiceCategory(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $serviceCategory = ServiceCategory::get();

        return response($serviceCategory, 200);
    }

    public function updateServiceCategory(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $serviceCategoryID = $request->serviceCategoryID;

        $fields = $request->validate([
            'serviceCategoryName' => ['required', 'string'],
        ]);

        $serviceCategory = ServiceCategory::where('id', $serviceCategoryID)->first();
        $serviceCategory->serviceCategoryName = $fields['serviceCategoryName'];
        $serviceCategory->save();
        
        return [
            'message'=> 'Service category updated',
        ];
    }

    public function deleteServiceCategory(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $serviceCategoryID = $request->serviceCategoryID;

        $service = Service::where('serviceCategoryID', $serviceCategoryID)->get();

        if(count($service) > 0){
            return response(
                [
                    'message'=> 'Cannot delete. Some service is linked to this category',
                ], 201
            );
        }else{
            ServiceCategory::where('id', $serviceCategoryID)->delete();
            return [
                'message'=> 'Service Category deleted successfully',
            ];
        }
    }
}
