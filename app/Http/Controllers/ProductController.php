<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $products = Product::search($request)->select("product")->paginate(config("app.pagination_number"));
        $categories = Product::groupBy('category')->pluck('category', 'category');
        $brands = Product::groupBy(["brand"])->pluck('brand', 'brand');
        $product_names = Product::groupBy(["product"])->pluck('product');
        $data = [];
        if ($request->ajax()) {
            foreach ($products as $item) {
                array_push($data, $item->product);
            }
            return response()->json(["data" => $data]);
        }
        return view('loadmore.index', compact(['products', "categories", "brands", "product_names"]));
    }


}
