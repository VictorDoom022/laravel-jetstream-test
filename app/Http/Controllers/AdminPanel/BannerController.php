<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\File; 

class BannerController extends Controller
{
    public function uploadBanner(Request $request){
        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        if($request->hasfile('bannerImage')){

            // get previous banner position
            $prevBanner = Banner::orderBy('bannerPosition', 'desc')->first();
            $newBannerPosition = 0;

            if($prevBanner != null){
                $newBannerPosition = $prevBanner->banner_position + 1;
            }

            $file = $request->file('bannerImage');
            
            //save the file into /public/upload/banner/{banner}
            $bannerImage = $file->getClientOriginalName();
            $filePath = 'upload/bannerImage/';
            $fullFilePath = $file->move(public_path($filePath), $bannerImage);

            // save the banner iamge dir into banner_imageSrc
            $banner = Banner::create([
                'bannerImageSrc' => $filePath . $bannerImage,
                'bannerPosition' => $newBannerPosition,
            ]);

            return [
                'message'=> 'Banner uploaded successfully',
            ];
        }else{
            return [
                'message'=> 'No file uploaded',
            ];
        }
    }

    public function getBanners(Request $request){
        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $banner = Banner::orderBy('bannerPosition', 'asc')->get();

        return response($banner, 200);
    }

    public function updateBannerPosition(Request $request){
        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $bannerList = $request->bannerList;

        // delete all contents of banner table
        Banner::whereNotNull('id')->delete();
        // save the all banner from the request bannerList
        for($i=0; $i < count($bannerList); $i++){
            $banner = Banner::create([
                'bannerImageSrc' => $bannerList[$i]['bannerImageSrc'],
                'bannerPosition' => $bannerList[$i]['bannerPosition'],
            ]);
        }

        return [
            'message'=> 'Banner position updated',
        ];
    }

    public function deleteBanner(Request $request){
        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }
        
        $bannerID = $request->bannerID;

        $banner = Banner::where('id', $bannerID)->first();
        $bannerImageFilePath = public_path() . "/" . $banner->bannerImageSrc;
        File::delete($bannerImageFilePath);
        $banner->delete();

        return [
            'message'=> 'Banner deleted successfully',
        ];
    }
}
