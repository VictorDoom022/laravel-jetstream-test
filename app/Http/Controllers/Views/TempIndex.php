<?php

namespace App\Http\Controllers\Views;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\AboutGallery;
use App\Models\HomepageService;

class TempIndex extends Controller
{
    public function index()
    {
        $banners = Banner::get();

        $returningBanners = [];

        foreach ($banners as $banner) {
            array_push($returningBanners, $banner["bannerImageSrc"]);
        }

        $serviceCategoryArray = array();

        $homepageService = HomepageService::get();
        $service = Service::get();

        for($i=0; $i < count($homepageService); $i++) {
            for($j=0; $j < count($service); $j++){
                if($homepageService[$i]->homepageService1 == $service[$j]->id){
                    $homepageService[$i]->homepageService1Name = $service[$j]->serviceName;
                }

                if($homepageService[$i]->homepageService2 == $service[$j]->id){
                    $homepageService[$i]->homepageService2Name = $service[$j]->serviceName;
                }

                if($homepageService[$i]->homepageService3 == $service[$j]->id){
                    $homepageService[$i]->homepageService3Name = $service[$j]->serviceName;
                }

                if($homepageService[$i]->homepageService4 == $service[$j]->id){
                    $homepageService[$i]->homepageService4Name = $service[$j]->serviceName;
                }
            }
        }

        $gallery = AboutGallery::orderBy('galleryImagePosition', 'asc')->get();

        return view("tempIndex", [
            "title" => "IDO'S Clinic",
            "description" => "Welcome to IDO's Clinic, a place where science, medicine and arts meet. State of the art lasers and aesthetics innovations to restore and enhance your skin, face and body.",
            "banners" => $returningBanners,
            "homepageService" => $homepageService,
            "gallery" => $gallery,
        ]);
    }
}
