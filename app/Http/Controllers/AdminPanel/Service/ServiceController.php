<?php

namespace App\Http\Controllers\AdminPanel\Service;

use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\ServicePoint;
use Illuminate\Support\Facades\File; 
use App\Models\HomepageService;

class ServiceController extends Controller
{
    public function addService(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $serviceCategoryID = $request->serviceCategoryID;
        $serviceFirstContentTitle = $request->serviceFirstContentTitle;
        $serviceFirstContentDescription = $request->serviceFirstContentDescription;
        $serviceSecondContentTitle = $request->serviceSecondContentTitle;
        $serviceSecondContentDescription = $request->serviceSecondContentDescription;
        $servicePointTitle = $request->servicePointTitle;
        $servicePointsArray = json_decode($request->servicePoints, true); // array, To be add into ServicePoint table
        $serviceBottomTitle = $request->serviceBottomTitle;
        $serviceBottomDescription = $request->serviceBottomDescription;
        
        $fields = $request->validate([
            'serviceName' => ['required', 'string'],
        ]);

        $service = Service::create([
            'serviceName' => $fields['serviceName'],
            'serviceCategoryID' => $serviceCategoryID,
            'serviceFirstContentTitle' => $serviceFirstContentTitle,
            'serviceFirstContentDescription' => $serviceFirstContentDescription,
            'serviceSecondContentTitle' => $serviceSecondContentTitle,
            'serviceSecondContentDescription' => $serviceSecondContentDescription,
            'servicePointTitle' => $servicePointTitle,
            'serviceBottomTitle' => $serviceBottomTitle,
            'serviceBottomDescription' => $serviceBottomDescription,
        ]);

        if($request->hasfile('serviceFirstContentAttachment')){
            $file = $request->file('serviceFirstContentAttachment');
            
            //save the file into /public/upload/services/{serviceID}/serviceFirstContentAttachment/{serviceFirstContentAttachment}
            $serviceFirstContentAttachment = $file->getClientOriginalName();
            $filePath = 'upload/services/'. $service->id .'/serviceFirstContentAttachment/';
            $fullFilePath = $file->move(public_path($filePath), $serviceFirstContentAttachment);

            $service->serviceFirstContentAttachment = $filePath . $serviceFirstContentAttachment;
            $service->save();
        }

        if($request->hasfile('serviceSecondContentAttachment')){
            $file = $request->file('serviceSecondContentAttachment');
            
            //save the file into /public/upload/services/{serviceID}/serviceSecondContentAttachment/{serviceSecondContentAttachment}
            $serviceSecondContentAttachment = $file->getClientOriginalName();
            $filePath = 'upload/services/'. $service->id .'/serviceSecondContentAttachment/';
            $fullFilePath = $file->move(public_path($filePath), $serviceSecondContentAttachment);

            $service->serviceSecondContentAttachment = $filePath . $serviceSecondContentAttachment;
            $service->save();
        }

        return $service->id; // return service id so that addServicePoint api can use
    }

    public function addServicePoint(Request $request){

        $serviceID = $request->serviceID;
        $servicePointTitle = $request->servicePointTitle;
        $servicePointDescription = $request->servicePointDescription;
        $servicePointAttachments = $request->servicePointAttachments;

        $servicePointTitleCount = $servicePointTitle == null ? 0 : count($servicePointTitle);
        $servicePointDescriptionCount = $servicePointDescription == null ? 0 : count($servicePointDescription);
        $servicePointAttachmentsCount = $servicePointAttachments == null ? 0 : count($servicePointAttachments);

        $highestCount = max($servicePointTitleCount, $servicePointDescriptionCount, $servicePointAttachmentsCount);

        for($i = 0; $i < $highestCount; $i++){
            if($servicePointTitle[$i] != null || $servicePointDescription[$i] != null || $servicePointAttachments[$i] != null){
                $servicePoint = ServicePoint::create([
                'serviceID' => $serviceID,
                'servicePointTitle' => $servicePointTitle[$i],
                'servicePointDescription' => $servicePointDescription[$i],
                'servicePointAttachmentSrc' => $servicePointAttachments[$i],
                ]);
            }
        }

        return [
            'message' => 'Service added successfully'
        ];      
    }

    public function getServices(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $service = Service::leftJoin("service_categories", "service_categories.id", "=", "services.serviceCategoryID")->get([
            'services.id',
            'services.serviceName',
            'service_categories.serviceCategoryName',
            'services.updated_at'
        ]);

        return response($service, 200);
    }

    public function deleteService(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $serviceID = $request->serviceID;

        // check if service is bind to homepageService
        $homepageService = HomepageService::where('homepageService1' , $serviceID)
                            ->orWhere('homepageService2' , $serviceID)
                            ->orWhere('homepageService3' , $serviceID)
                            ->orWhere('homepageService4' , $serviceID)
                            ->get();

        if(count($homepageService) > 0){
            return response(
                [
                    'message' => 'Cannot delete. Some homepage service is linked to this service'
                ], 201
            );
        }else{
            // test if the file doesnt exist
            $service = Service::where('id', $serviceID)->first();
            if($service->serviceFirstContentAttachment != null){
                $firstContentFilePath = public_path() . "/" . $service->serviceFirstContentAttachment;
                File::delete($firstContentFilePath);
            }
            
            if($service->serviceSecondContentAttachment != null){
                $secondContentFilePath = public_path() . "/" . $service->serviceSecondContentAttachment;
                File::delete($secondContentFilePath);
            }

            $servicePoints = ServicePoint::where('serviceID', $service->id)->get();
            $servicePoints->each->delete();

            $service->delete();

            return [
                'message'=> 'Service deleted successfully',
            ];
        }
    }

    public function getEditServiceFormDataByServiceID(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $serviceID = $request->serviceID;
        
        $service = Service::where('services.id', $serviceID)
            ->leftJoin("service_categories", "service_categories.id", "=", "services.serviceCategoryID")
            ->first([
                'services.*',
                'service_categories.serviceCategoryName',
            ]);

        $servicePoint = ServicePoint::where("serviceID", $serviceID)->get();

        $serviceCategory = ServiceCategory::get();

        return [
            "service" => $service,
            "servicePoint" => $servicePoint,
            "categoryList" => $serviceCategory,
        ];
    }

    public function editService(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $serviceID = $request->serviceID;
        $serviceName = $request->serviceName;
        $serviceCategoryID = $request->serviceCategoryID;
        $serviceFirstContentTitle = $request->serviceFirstContentTitle;
        $serviceFirstContentDescription = $request->serviceFirstContentDescription;
        $serviceSecondContentTitle = $request->serviceSecondContentTitle;
        $serviceSecondContentDescription = $request->serviceSecondContentDescription;
        $servicePointTitle = $request->servicePointTitle;
        $serviceBottomTitle = $request->serviceBottomTitle;
        $serviceBottomDescription = $request->serviceBottomDescription;

        $fields = $request->validate([
            'serviceName' => ['required', 'string'],
        ]);

        $service = Service::where('id', $serviceID)->first();
        $service->serviceName = $fields['serviceName'];
        $service->serviceCategoryID = $serviceCategoryID;
        $service->serviceFirstContentTitle = $serviceFirstContentTitle;
        $service->serviceFirstContentDescription = $serviceFirstContentDescription;
        $service->serviceSecondContentTitle = $serviceSecondContentTitle;
        $service->serviceSecondContentDescription = $serviceSecondContentDescription;
        $service->servicePointTitle = $servicePointTitle;
        $service->serviceBottomTitle = $serviceBottomTitle;
        $service->serviceBottomDescription = $serviceBottomDescription;

        if($request->hasfile('serviceFirstContentAttachment')){
            //delete the previous file if user upload a new image
            $file = $request->file('serviceFirstContentAttachment');
            $previousFirstContentFilePath = public_path() . "/" . $service->serviceFirstContentAttachment;
            File::delete($previousFirstContentFilePath);
            
            //save the file into /public/upload/services/{serviceID}/serviceFirstContentAttachment/{serviceFirstContentAttachment}
            $serviceFirstContentAttachment = $file->getClientOriginalName();
            $filePath = 'upload/services/'. $service->id .'/serviceFirstContentAttachment/';
            $fullFilePath = $file->move(public_path($filePath), $serviceFirstContentAttachment);

            $service->serviceFirstContentAttachment = $filePath . $serviceFirstContentAttachment;
            $service->save();
        }

        if($request->serviceFirstContentAttachment == null){
            // if serviceFirstContentAttachment is null means user remove the image
            $previousFirstContentFilePath = public_path() . "/" . $service->serviceFirstContentAttachment;
            File::delete($previousFirstContentFilePath);

            $service->serviceFirstContentAttachment = null;
        }

        if($request->hasfile('serviceSecondContentAttachment')){
            //delete the previous file if user upload a new image
            $file = $request->file('serviceSecondContentAttachment');
            $previousSecondContentFilePath = public_path() . "/" . $service->serviceSecondContentAttachment;
            File::delete($previousSecondContentFilePath);
            
            //save the file into /public/upload/services/{serviceID}/serviceSecondContentAttachment/{serviceSecondContentAttachment}
            $serviceSecondContentAttachment = $file->getClientOriginalName();
            $filePath = 'upload/services/'. $service->id .'/serviceSecondContentAttachment/';
            $fullFilePath = $file->move(public_path($filePath), $serviceSecondContentAttachment);

            $service->serviceSecondContentAttachment = $filePath . $serviceSecondContentAttachment;
            $service->save();
        }

        if($request->serviceSecondContentAttachment == null){
            // if serviceFirstContentAttachment is null means user remove the image
            $previousSecondContentFilePath = public_path() . "/" . $service->serviceSecondContentAttachment;
            File::delete($previousSecondContentFilePath);

            $service->serviceSecondContentAttachment = null;
        }

        $service->save();
    }

    public function editServicePoint(Request $request){
        $servicePointID = $request->servicePointID;
        $serviceID = $request->serviceID;
        $servicePointTitle = $request->servicePointTitle;
        $servicePointDescription = $request->servicePointDescription;
        $servicePointAttachments = $request->servicePointAttachments;

        $servicePointTitleCount = $servicePointTitle == null ? 0 : count($servicePointTitle);
        $servicePointDescriptionCount = $servicePointDescription == null ? 0 : count($servicePointDescription);
        $servicePointAttachmentsCount = $servicePointAttachments == null ? 0 : count($servicePointAttachments);

        $highestCount = max($servicePointTitleCount, $servicePointDescriptionCount, $servicePointAttachmentsCount);

        for($i = 0; $i < $highestCount; $i++){ // since servicePointTitle is required we use the length of that array for our loop
            $servicePoint = ServicePoint::where('id', $servicePointID[$i])->first();
            if($servicePoint != null){
                // if servicePoint exists, update it

                // check if all fields are empty. If all empty, delete the service point
                if($serviceID != null && ($servicePointTitle[$i] != null || $servicePointDescription[$i] != null || $servicePointAttachments[$i] != null)) {
                    $servicePoint->servicePointTitle = $servicePointTitle[$i];
                    $servicePoint->servicePointDescription = $servicePointDescription[$i];
                    $servicePoint->servicePointAttachmentSrc = $servicePointAttachments[$i];
                    $servicePoint->save();
                }else{
                    // delete the service point
                    $servicePoint->delete();
                }
            }else{
                // if it doesnt exist, create new service point
                if($servicePointTitle[$i] != null || $servicePointDescription[$i] != null || $servicePointAttachments[$i] != null){
                    $newServicePoint = ServicePoint::create([
                        'serviceID' => $serviceID,
                        'servicePointTitle' => $servicePointTitle[$i],
                        'servicePointDescription' => $servicePointDescription[$i],
                        'servicePointAttachmentSrc' => $servicePointAttachments[$i],
                    ]);
                }
            }
        }

        return [
            'message' => 'Service updated successfully'
        ];
    }

    public function deleteServicePoint(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $servicePointID = $request->servicePointID;

        $servicePoint = ServicePoint::where('id', $servicePointID)->first();
        // delete service point attachment
        $servicePointAttachment = public_path() . "/" . $servicePoint->servicePointAttachmentSrc;
        File::delete($servicePointAttachment);
        $servicePoint->delete();
    }
}
