<?php

namespace App\Http\Controllers\Views;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\AboutGallery;

class AboutController extends Controller
{
    public function index()
    {
        $doctor = Doctor::get();
        $gallery = AboutGallery::orderBy('galleryImagePosition', 'asc')->get();

        return view('about', [
            "title" => "About - IDO'S Clinic",
            "description" => "Welcome to IDO's Clinic, a place where science, medicine and arts meet. State of the art lasers and aesthetics innovations to restore and enhance your skin, face and body.",
            'doctors' => $doctor,
            "gallery" => $gallery,
        ]);
    }
    
    public function doctorPage($id)
    {
        $doctor = Doctor::where('id', $id)->first();

        if ($doctor == null) {
            abort(404);
        }

        return view('doctor', [
            "title" => $doctor->doctorName . " - Doctor - IDO'S Clinic",
            "description" => "Welcome to IDO's Clinic, a place where science, medicine and arts meet. State of the art lasers and aesthetics innovations to restore and enhance your skin, face and body.",
            'doctor' => $doctor
        ]);
    }
}
