<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\ProductRequest;
use App\ProductGallery;

class DashboardProductController extends Controller
{
    //
    public function index(){
        $products = Product::with(['galleries','category'])
                                ->where('users_id', Auth::user()->id)
                                ->get();
        return view('pages.dashboard-products', [
            'products' => $products
        ]);
    }

    public function details(){
        return view('pages.dashboard-products-details');
    }

    public function create(){
        $kategori = Category::all();
        return view('pages.dashboard-products-create',[
            'categories' => $kategori
        ]);
    }

      public function store(ProductRequest $request)
    {
        //
        $data = $request->all();

        $data['slug'] = Str::slug($request->name);

        $product = Product::create($data);

        $gallery = [
            'products_id' => $product->id,
            'photo' => $request->file('photo')->store('assets/product', 'public')
        ];

        ProductGallery::create($gallery);

        return redirect()->route('dashboard-product');
    }
}