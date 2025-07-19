<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json([]);
        }

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'condition') // Select only the necessary fields
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}
