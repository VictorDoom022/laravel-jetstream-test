<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Models\AboutGallery;
use Illuminate\Support\Facades\File; 

class AboutGalleryController extends Controller
{
    public function uploadGalleryImage(Request $request){
        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        if($request->hasfile('galleryImage')){

            // get previous galleryImage position
            $prevGalleryImage = AboutGallery::orderBy('galleryImagePosition', 'desc')->first();
            $newGalleryImagePosition = 0;

            if($prevGalleryImage != null){
                $newGalleryImagePosition = $prevGalleryImage->galleryImagePosition + 1;
            }

            $file = $request->file('galleryImage');
            
            //save the file into /public/upload/aboutGalleryImage/{galleryImageID}
            $galleryImage = $file->getClientOriginalName();
            $filePath = 'upload/aboutGalleryImage/';
            $fullFilePath = $file->move(public_path($filePath), $galleryImage);

            // save the gallery image dir into galleryImageSrc
            $aboutGallery = AboutGallery::create([
                'galleryImageSrc' => $filePath . $galleryImage,
                'galleryImagePosition' => $newGalleryImagePosition,
            ]);

            return [
                'message'=> 'Gallery image uploaded successfully',
            ];
        }else{
            return [
                'message'=> 'No file uploaded',
            ];
        }
    }

    public function getGalleryImages(Request $request){
        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $aboutGallery = AboutGallery::orderBy('galleryImagePosition', 'asc')->get();

        return response($aboutGallery, 200);
    }

    public function updateGalleryImagePosition(Request $request){
        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $aboutGalleryImageList = $request->aboutGalleryImageList;

        // delete all contents of about_gallery table
        AboutGallery::whereNotNull('id')->delete();
        // save the all galleryImage from the request aboutGalleryImageList
        for($i=0; $i < count($aboutGalleryImageList); $i++){
            $aboutGallery = AboutGallery::create([
                'galleryImageSrc' => $aboutGalleryImageList[$i]['galleryImageSrc'],
                'galleryImagePosition' => $aboutGalleryImageList[$i]['galleryImagePosition'],
            ]);
        }

        return [
            'message'=> 'Gallery image position updated',
        ];
    }

    public function deleteGalleryImage(Request $request){
        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }
        
        $galleryImageID = $request->galleryImageID;

        $aboutGallery = AboutGallery::where('id', $galleryImageID)->first();
        $bannerImageFilePath = public_path() . "/" . $aboutGallery->galleryImageSrc;
        File::delete($bannerImageFilePath);
        $aboutGallery->delete();

        return [
            'message'=> 'Gallery image deleted successfully',
        ];
    }
}
