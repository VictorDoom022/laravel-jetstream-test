<?php

namespace App\Http\Controllers\Views;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServicePoint;

class ServiceDetailController extends Controller
{
    public function index($id)
    {
        $service = Service::where('id', $id)->first();

        if ($service == null) {
            abort(404);
        }

        $servicePoint = ServicePoint::where('serviceID', $id)->get();

        return view('serviceDetail', [
            "title" => $service->serviceName . " - Service - IDO'S Clinic",
            "description" => $service->serviceFirstContentDescription ?: "Welcome to IDO's Clinic, a place where science, medicine and arts meet. State of the art lasers and aesthetics innovations to restore and enhance your skin, face and body.",
            'service' => $service,
            'servicePoints' => $servicePoint
        ]);
    }
}
