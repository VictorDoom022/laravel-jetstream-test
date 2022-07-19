<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function setCompanyInfo(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $companyEmail = $request->companyEmail;
        $companyFacebook = $request->companyFacebook;
        $companyInstagram = $request->companyInstagram;
        $companyYoutube = $request->companyYoutube;
        $companyWhatsappContactNumber = $request->companyWhatsappContactNumber;
        $companyOperatingHour = $request->companyOperatingHour;

        //check if the column exist or not
        $company = Company::first();

        if($company == null){
            //if doesnt exist, create new one (usually first time use)
            Company::create([
                'companyEmail' => $companyEmail,
                'companyFacebook' => $companyFacebook,
                'companyInstagram' => $companyInstagram,
                'companyYoutube' => $companyYoutube,
                'companyWhatsappContactNumber' => $companyWhatsappContactNumber,
                'companyOperatingHour' => $companyOperatingHour,
            ]);
        }else{
            $company->companyEmail = $companyEmail;
            $company->companyFacebook = $companyFacebook;
            $company->companyInstagram = $companyInstagram;
            $company->companyYoutube = $companyYoutube;
            $company->companyWhatsappContactNumber = $companyWhatsappContactNumber;
            $company->companyOperatingHour = $companyOperatingHour;
            $company->save();
        }

        return [
            'message' => 'Company info updated'
        ];
    }

    public function getCompanyInfo(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }
        
        $company = Company::first();

        return $company;
    }
}
