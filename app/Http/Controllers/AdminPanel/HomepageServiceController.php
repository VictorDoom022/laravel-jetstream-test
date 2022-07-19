<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\HomepageService;
use Illuminate\Support\Facades\File;

class HomepageServiceController extends Controller
{
    public function getServiceDetail(Request $request){
        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $service = Service::get();
        
        $homePageService = HomepageService::get();

        return response([
            'homePageService' => $homePageService,
            'services' => $service,
        ], 200);
    }

    public function addHomePageService(Request $request){
        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $homepageServiceTitle = $request->homepageServiceTitle;
        $homepageService1 = $request->homepageService1;
        $homepageService2 = $request->homepageService2;
        $homepageService3 = $request->homepageService3;
        $homepageService4 = $request->homepageService4;

        // return $homepageService1;
        // delete all contents since this table will only have 3 columns staticly

        $homePageService = HomepageService::create([
            'homepageServiceTitle' => $homepageServiceTitle,
            'homepageService1' => $homepageService1,
            'homepageService2' => $homepageService2,
            'homepageService3' => $homepageService3,
            'homepageService4' => $homepageService4,
        ]);

        if($request->hasfile('homepageServiceImage')){
            $file = $request->file('homepageServiceImage');

            //save the file into /public/upload/doctors/{id}/
            $homepageServiceImage = $file->getClientOriginalName();
            $filePath = 'upload/homePageService/' . $homePageService->id . '/';
            $fullFilePath = $file->move(public_path($filePath), $homepageServiceImage);

            $homePageService->homepageServiceImage = $filePath . $homepageServiceImage;
            $homePageService->save();
        }
    
        return [
            'message' => 'Homepage service added successfully'
        ];
    }

    public function deleteHomepageService(Request $request){
        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $homepageServiceID = $request->homepageServiceID;

        $homepageService = HomepageService::where('id', $homepageServiceID)->first();
        if($homepageService->homepageServiceImage != null){
            $homepageServiceFilePath = public_path() . "/" . $homepageService->homepageServiceImage;
            File::delete($homepageServiceFilePath);
        }

        $homepageService->delete();

        return [
            'message'=> 'Homepage Service deleted successfully',
        ];
    }

    public function getHomepageServiceByID(Request $request){
        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $homepageServiceID = $request->homepageServiceID;
        
        $homePageService = HomepageService::where('id', $homepageServiceID)->first();
        $service = Service::get();

        return response([
            'homePageService' => $homePageService,
            'services' => $service,
        ], 200);
    }

    public function updateHomepageService(Request $request){
        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $homepageServiceID = $request->homepageServiceID;
        $homepageServiceTitle = $request->homepageServiceTitle;
        $homepageService1 = $request->homepageService1;
        $homepageService2 = $request->homepageService2;
        $homepageService3 = $request->homepageService3;
        $homepageService4 = $request->homepageService4;

        $homePageService = HomepageService::where('id', $homepageServiceID)->first();
        $homePageService->homepageServiceTitle = $homepageServiceTitle;
        $homePageService->homepageService1 = $homepageService1;
        $homePageService->homepageService2 = $homepageService2;
        $homePageService->homepageService3 = $homepageService3;
        $homePageService->homepageService4 = $homepageService4;

        if($request->hasfile('homepageServiceImage')){
            // delete the previous file if user upload new image
            $file = $request->file('homepageServiceImage');
            $homepageServiceFilePath = public_path() . "/" . $homePageService->homepageServiceImage;
            File::delete($homepageServiceFilePath);

            //save the file into /public/upload/doctors/{id}/
            $homepageServiceImage = $file->getClientOriginalName();
            $filePath = 'upload/homePageService/' . $homePageService->id . '/';
            $fullFilePath = $file->move(public_path($filePath), $homepageServiceImage);

            $homePageService->homepageServiceImage = $filePath . $homepageServiceImage;
            $homePageService->save();
        }

        if($request->homepageServiceImage == null){
            $file = $request->file('homepageServiceImage');
            $prevHomepageImage = public_path() . "/" . $homePageService->homepageServiceImage;
            File::delete($prevHomepageImage);

            $homePageService->homepageServiceImage = null;
        }

        $homePageService->save();

        return [
            'message'=> 'Homepage Service updated successfully',
        ];
    }
}
