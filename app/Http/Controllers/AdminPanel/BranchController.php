<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    public function addBranch(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $branchAddress = $request->branchAddress;

        $fields = $request->validate([
            'branchName' => ['required', 'string'],
            'branchContact' => ['required', 'string'],
        ]);

        $branch = Branch::create([
            'branchName' => $fields['branchName'],
            'branchContact' => $fields['branchContact'],
            'branchAddress' => $branchAddress,
        ]);

        return [
            'message'=> 'Branch created successfully',
        ];
    }

    public function getBranches(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $branch = Branch::get();

        return response($branch, 200);
    }

    public function getBranch(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $branchID = $request->branchID;

        $branch = Branch::where('id', $branchID)->first();

        return response($branch, 200);
    }

    public function updateBranch(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $branchID = $request->branchID;
        $branchAddress = $request->branchAddress;

        $fields = $request->validate([
            'branchName' => ['required', 'string'],
            'branchContact' => ['required', 'string'],
        ]);

        $branch = Branch::where('id', $branchID)->first();
        $branch->branchName = $fields['branchName'];
        $branch->branchContact = $fields['branchContact'];
        $branch->branchAddress = $branchAddress;
        $branch->save();
        
        return [
            'message'=> 'Branch updated',
        ];
    }

    public function deleteBranch(Request $request){

        if($request->user()->lockStatus == 1){
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $branchID = $request->branchID;

        Branch::where('id', $branchID)->delete();

        return [
            'message'=> 'Branch deleted successfully',
        ];
    }
}
