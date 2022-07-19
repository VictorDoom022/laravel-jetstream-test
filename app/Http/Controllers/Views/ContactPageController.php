<?php

namespace App\Http\Controllers\Views;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Branch;

class ContactPageController extends Controller
{
    public function index()
    {
        $company = Company::first();
        $branch = Branch::get();

        return view('contact', [
            'title' => "Contact Us - IDO'S Clinic",
            'description' => "Welcome to IDO's Clinic, a place where science, medicine and arts meet. State of the art lasers and aesthetics innovations to restore and enhance your skin, face and body.",
            'company' => $company,
            'branches' => $branch,
        ]);
    }
}
