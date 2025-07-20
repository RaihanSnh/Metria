<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::select('id', 'name', 'condition')->orderBy('name')->get();
        return view('posts.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'caption' => 'nullable|string',
            'media' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480', // 20MB Max
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        DB::beginTransaction();
        try {
            $mediaPath = $request->file('media')->store('posts', 'public');

            $post = Auth::user()->posts()->create([
                'caption' => $validated['caption'],
                'post_image_url' => $mediaPath,
            ]);

            if (!empty($validated['products'])) {
                foreach ($validated['products'] as $productId) {
                    $post->items()->create([
                        'item_type' => Product::class, // Using the class name for the morph map
                        'item_id' => $productId,
                    ]);
                }
            }

            DB::commit();

            // Redirect to the new feed page, which we will create next.
            return redirect()->route('feed')->with('success', 'Post created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Temporarily show the real error for debugging purposes.
            return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage())->withInput();
        }
    }
} 