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
        $data = product::get()->toJson();
        return view('template.index', compact('title'));
    }
    
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|numeric',
            'product_name' => 'required',
            'price' => 'required',
            'stocks' => 'required|numeric'
        ]);
        if($validator->fails()) {
            return response()->json(['status'=>'error', 'message'=>'Failed to add products.', 'errors'=>$validator->errors()]);
        } else {
            $data = $request->all();
            $new = product::create($data);
            return response()->json(['status'=>'success',   'message'=>'Product added.', 'new'=>$new]);
        }
    }

    public function delete(Request $request) {
        $toDelete = product::findOrFail($request->id);
        $toDelete->delete();
        return redirect()->route('product');
    }
}
