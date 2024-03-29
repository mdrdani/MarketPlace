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

    public function details(Request $request, $id){
        $product = Product::with(['galleries','user','category'])->findOrFail($id);
        $categories = Category::all();

        return view('pages.dashboard-products-details',[
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function uploadgallery(Request $request)
    {
         $data = $request->all();

        $data['photo'] = $request->file('photo')->store('assets/productgallery','public');

        ProductGallery::create($data);

        return redirect()->route('dashboard-product-details', $request->products_id);
    }

    public function deletegallery(Request $request, $id)
    {
        $item = ProductGallery::findOrFail($id);
        $item->delete();

        return redirect()->route('dashboard-product-details', $item->products_id);
    }

    public function create(){
        $kategori = Category::all();
        return view('pages.dashboard-products-create',[
            'categories' => $kategori
        ]);
    }

    public function update(Request $request, $id)
    {
        //
        $data = $request->all();

        $item = Product::findOrFail($id);

        $data['slug'] = Str::slug($request->name);

        $item->update($data);

        return redirect()->route('dashboard-product');
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