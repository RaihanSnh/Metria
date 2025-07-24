<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        Log::info('Post creation attempt started', [
            'user_id' => Auth::id(),
            'request_data' => $request->except(['media'])
        ]);

        $validated = $request->validate([
            'caption' => 'nullable|string',
            'media' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480', // 20MB Max
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        Log::info('Post validation passed', ['validated_data' => collect($validated)->except(['media'])->toArray()]);

        DB::beginTransaction();
        try {
            Log::info('Starting file upload');
            $mediaPath = $request->file('media')->store('posts', 'public');
            Log::info('File uploaded successfully', ['media_path' => $mediaPath]);

            Log::info('Creating post record', [
                'user_id' => Auth::id(),
                'caption' => $validated['caption'],
                'media_path' => $mediaPath
            ]);

            $post = Auth::user()->posts()->create([
                'caption' => $validated['caption'],
                'post_image_url' => $mediaPath,
            ]);

            Log::info('Post created successfully', ['post_id' => $post->id]);

            if (!empty($validated['products'])) {
                Log::info('Adding tagged products', ['products' => $validated['products']]);
                foreach ($validated['products'] as $productId) {
                    $postItem = $post->items()->create([
                        'item_type' => 'product', // Using string for morph map
                        'item_id' => $productId,
                        'position_x' => 50.00, // Default center position
                        'position_y' => 50.00, // Default center position
                    ]);
                    Log::info('Product tagged', ['post_item_id' => $postItem->id, 'product_id' => $productId]);
                }
            }

            DB::commit();
            Log::info('Post creation transaction committed successfully');

            // Redirect to the new feed page, which we will create next.
            return redirect()->route('feed')->with('success', 'Post created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            // Temporarily show the real error for debugging purposes.
            return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $products = Product::select('id', 'name', 'condition')->orderBy('name')->get();
        return view('posts.edit', compact('post', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'caption' => 'nullable|string',
        ]);

        $post->update($validated);

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        
        $post->delete();

        return redirect()->route('feed')->with('success', 'Post deleted successfully!');
    }

    /**
     * Display a listing of posts (index).
     */
    public function index()
    {
        $posts = Post::with('user', 'items.item')->latest()->paginate(15);
        return view('posts.index', compact('posts'));
    }
}