<?php

namespace App\Http\Controllers;

use App\product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request) {
        $title = "Product List";
        if($request->ajax()) {
            $data = product::latest()->get();
            return datatables()->of($data)->make(true);
        }  
        return view('template.index', compact('title'));
    }
    
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|numeric|unique:products,product_id',
            'product_name' => 'required',
            'price' => 'required|numeric',
            'stocks' => 'required|numeric'
        ]);
        if($validator->fails()) {
            return response()->json(['status'=>'error', 'message'=>'Failed to add products.', 'errors'=>$validator->errors()]);
        } else {
            $data = $request->all();
            product::create($data);
            return response()->json(['status'=>'success', 'message'=>'Product added.']);
        }
    }

    public function delete(Request $request) {
        $toDelete = product::findOrFail($request->id);
        $toDelete->delete();
        return response()->json(['message'=>'Delete success.']);
    }
}
