<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\Product;
use Dotenv\Util\Str;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        
        return view('products.show', compact('product'));
    }

    public function search(String $search)
    {
        return view('products.search', compact('search'));
    }
}
