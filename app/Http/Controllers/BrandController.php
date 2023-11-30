<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class BrandController extends Controller
{
    //
    public function AllBrand(){
        $brands = Brand::latest()->paginate(10);
      
        return view('admin.brand.index',compact('brands'));
       
    }

    
    public function AddBrand(Request $request)
{
    $validated = $request->validate([
        'brand_name' => 'required|unique:brands|max:255',
        'brand_image' => 'required|mimes:jpg,jpeg,png',
    ], [
        'brand_name.required' => 'Please input brand name.',
        'brand_name.max' => 'Brand name must be less than 255 characters.',
        'brand_image.mimes' => 'The file must be of type: jpg, jpeg, or png.',
    ]);

    try {
        $brand_image = $request->file('brand_image');
        $image_name = hexdec(uniqid()) . '.' . $brand_image->getClientOriginalExtension();
        $up_loc = 'image/brand/';
        $brand_image->move($up_loc, $image_name);

        Brand::create([
            'brand_name' => $request->brand_name,
            'brand_image' => $up_loc . $image_name,
        ]);

        return redirect()->back()->with('success', 'Brand inserted successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}
public function edit($id){
    $brand = Brand::find($id);
    return view('admin.brand.editBrand', compact('brand'));
}

public function update(Request $request, $id){
    $brand = Brand::find($id);

    $validated = $request->validate([
        'brand_name' => 'required|unique:brands,brand_name,'.$brand->id.'|max:255',
        'brand_image' => 'nullable|mimes:jpg,jpeg,png',
    ], [
        'brand_name.required' => 'Please input brand name.',
        'brand_name.max' => 'Brand name must be less than 255 characters.',
        'brand_image.mimes' => 'The file must be of type: jpg, jpeg, or png.',
    ]);

    $brand->brand_name = $request->brand_name;

    if ($request->hasFile('brand_image')) {
        // Upload and update brand image if provided
        $brand_image = $request->file('brand_image');
        $image_name = hexdec(uniqid()) . '.' . $brand_image->getClientOriginalExtension();
        $up_loc = 'image/brand/';
        $brand_image->move($up_loc, $image_name);

        $brand->brand_image = $up_loc . $image_name;
    }

    $brand->save();

    return redirect()->route('brand')->with('success', 'Brand updated successfully.');
}
public function delete($id){
    $brand = Brand::find($id);

    // Delete the brand image file (optional)
    if (file_exists($brand->brand_image)) {
        unlink($brand->brand_image);
    }

    $brand->delete();

    return redirect()->route('brand')->with('success', 'Brand deleted successfully.');
}
}
