<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\File;

class DoctorController extends Controller
{
    public function addDoctor(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $doctorCredentials = $request->doctorCredentials;
        $doctorClinicalInterest = $request->doctorClinicalInterest;
        $doctorAbout = $request->doctorAbout;


        $fields = $request->validate([
            'doctorName' => ['required', 'string'],
            'doctorPosition' => ['required', 'string'],
        ]);

        $doctor = Doctor::create([
            'doctorName' => $fields['doctorName'],
            'doctorPosition' => $fields['doctorPosition'],
            'doctorCredentials' => $doctorCredentials,
            'doctorClinicalInterest' => $doctorClinicalInterest,
            'doctorAbout' => $doctorAbout,
        ]);

        if($request->hasfile('doctorImageSrc')){
            $file = $request->file('doctorImageSrc');

            //save the file into /public/upload/doctors/{id}/
            $doctorImage = $file->getClientOriginalName();
            $filePath = 'upload/doctors/' . $doctor->id . '/';
            $fullFilePath = $file->move(public_path($filePath), $doctorImage);

            $doctor->doctorImageSrc = $filePath . $doctorImage;
            $doctor->save();
        }

        return [
            'message'=> 'Doctor created successfully',
        ];
    }

    public function getDoctors(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }
        
        $doctor = Doctor::get();

        return response($doctor, 200);
    }

    public function deleteDoctor(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $doctorID = $request->doctorID;

        $doctor = Doctor::where('id', $doctorID)->first();
        if($doctor->doctorImageSrc != null){
            $doctorFilePath = public_path() . "/" . $doctor->doctorImageSrc;
            File::delete($doctorFilePath);
        }

        $doctor->delete();

        return [
            'message'=> 'Doctor deleted successfully',
        ];
    }

    public function getDoctor(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $doctorID = $request->doctorID;

        $doctor = Doctor::where('id', $doctorID)->first();

        return response($doctor, 200);
    }

    public function updateDoctor(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $doctorID = $request->doctorID;
        $doctorCredentials = $request->doctorCredentials;
        $doctorClinicalInterest = $request->doctorClinicalInterest;
        $doctorAbout = $request->doctorAbout;


        $fields = $request->validate([
            'doctorName' => ['required', 'string'],
            'doctorPosition' => ['required', 'string'],
        ]);

        $doctor = Doctor::where('id', $doctorID)->first();
        $doctor->doctorName = $fields['doctorName'];
        $doctor->doctorPosition = $fields['doctorPosition'];
        $doctor->doctorCredentials = $doctorCredentials;
        $doctor->doctorClinicalInterest = $doctorClinicalInterest;
        $doctor->doctorAbout = $doctorAbout;

        if($request->hasfile('doctorImageSrc')){
            // delete the previous file if user upload new image
            $file = $request->file('doctorImageSrc');
            $prevDoctorImageSrc = public_path() . "/" . $doctor->doctorImageSrc;
            File::delete($prevDoctorImageSrc);

            //save the file into /public/upload/doctors/{id}/
            $doctorImage = $file->getClientOriginalName();
            $filePath = 'upload/doctors/' . $doctor->id . '/';
            $fullFilePath = $file->move(public_path($filePath), $doctorImage);

            $doctor->doctorImageSrc = $filePath . $doctorImage;
            $doctor->save();
        }

        if($request->doctorImageSrc == null){
            // delete the previous file if user upload new image
            $file = $request->file('doctorImageSrc');
            $prevDoctorImageSrc = public_path() . "/" . $doctor->doctorImageSrc;
            File::delete($prevDoctorImageSrc);

            $doctor->doctorImageSrc = null;
        }
        $doctor->save();

        return [
            'message'=> 'Doctor updated successfully',
        ];
    }
}
