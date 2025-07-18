<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostItem;
use App\Models\Product;
use App\Models\DigitalWardrobeItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of posts (social feed)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $posts = Post::with([
            'user',
            'postItems.item',
            'taggedProducts'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        // Transform posts to include tagged items with proper polymorphic data
        $posts->getCollection()->transform(function ($post) {
            $post->tagged_items = $post->postItems->map(function ($postItem) {
                $item = null;
                
                switch ($postItem->item_type) {
                    case 'product':
                        $item = Product::find($postItem->item_id);
                        break;
                    case 'digital_wardrobe_item':
                        $item = DigitalWardrobeItem::find($postItem->item_id);
                        break;
                }
                
                return [
                    'id' => $postItem->id,
                    'type' => $postItem->item_type,
                    'item' => $item,
                    'position_x' => $postItem->position_x,
                    'position_y' => $postItem->position_y,
                    'affiliate_code' => $postItem->affiliate_code,
                    'has_affiliate' => !empty($postItem->affiliate_code),
                ];
            });
            
            return $post;
        });

        return response()->json([
            'success' => true,
            'data' => $posts,
        ]);
    }

    /**
     * Store a newly created post (OOTD)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'caption' => 'required|string|max:1000',
            'is_sponsored' => 'boolean',
            'tagged_items' => 'array',
            'tagged_items.*.type' => 'required|in:product,digital_wardrobe_item',
            'tagged_items.*.item_id' => 'required|integer',
            'tagged_items.*.position_x' => 'required|numeric|between:0,100',
            'tagged_items.*.position_y' => 'required|numeric|between:0,100',
            'tagged_items.*.affiliate_code' => 'nullable|string|exists:users,affiliate_code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = $request->user();

            // Upload image
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('posts', 'public');
            }

            // Create post
            $post = Post::create([
                'user_id' => $user->id,
                'post_image_url' => $imagePath ? Storage::url($imagePath) : null,
                'caption' => $request->caption,
                'is_sponsored' => $request->is_sponsored ?? false,
            ]);

            // Add tagged items
            if ($request->has('tagged_items')) {
                foreach ($request->tagged_items as $taggedItem) {
                    // Validate that the item exists
                    $itemExists = false;
                    switch ($taggedItem['type']) {
                        case 'product':
                            $itemExists = Product::where('id', $taggedItem['item_id'])->exists();
                            break;
                        case 'digital_wardrobe_item':
                            $itemExists = DigitalWardrobeItem::where('id', $taggedItem['item_id'])
                                ->where('user_id', $user->id)
                                ->exists();
                            break;
                    }

                    if (!$itemExists) {
                        continue;
                    }

                    PostItem::create([
                        'post_id' => $post->id,
                        'item_type' => $taggedItem['type'],
                        'item_id' => $taggedItem['item_id'],
                        'position_x' => $taggedItem['position_x'],
                        'position_y' => $taggedItem['position_y'],
                        'affiliate_code' => $taggedItem['affiliate_code'] ?? null,
                    ]);
                }
            }

            DB::commit();

            $post->load(['user', 'postItems']);

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully',
                'data' => $post,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified post
     */
    public function show(Post $post)
    {
        $post->load([
            'user',
            'postItems',
            'taggedProducts'
        ]);

        // Get detailed item information
        $post->tagged_items = $post->postItems->map(function ($postItem) {
            $item = null;
            
            switch ($postItem->item_type) {
                case 'product':
                    $item = Product::with(['sizeCharts', 'genres', 'materials'])->find($postItem->item_id);
                    break;
                case 'digital_wardrobe_item':
                    $item = DigitalWardrobeItem::find($postItem->item_id);
                    break;
            }
            
            return [
                'id' => $postItem->id,
                'type' => $postItem->item_type,
                'item' => $item,
                'position_x' => $postItem->position_x,
                'position_y' => $postItem->position_y,
                'affiliate_code' => $postItem->affiliate_code,
                'has_affiliate' => !empty($postItem->affiliate_code),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $post,
        ]);
    }

    /**
     * Update the specified post
     */
    public function update(Request $request, Post $post)
    {
        $user = $request->user();

        // Check if user owns this post
        if ($post->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'caption' => 'string|max:1000',
            'is_sponsored' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $post->update($request->only(['caption', 'is_sponsored']));

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'data' => $post,
        ]);
    }

    /**
     * Remove the specified post
     */
    public function destroy(Post $post)
    {
        $user = request()->user();

        // Check if user owns this post
        if ($post->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Delete image file if exists
        if ($post->post_image_url) {
            $imagePath = str_replace('/storage/', '', $post->post_image_url);
            Storage::disk('public')->delete($imagePath);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully',
        ]);
    }

    /**
     * Get posts by user (profile posts)
     */
    public function getUserPosts(Request $request, User $user)
    {
        $posts = Post::where('user_id', $user->id)
            ->with([
                'user',
                'postItems',
                'taggedProducts'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $posts,
        ]);
    }

    /**
     * Get posts with specific product tagged
     */
    public function getProductPosts(Request $request, Product $product)
    {
        $posts = Post::whereHas('postItems', function ($query) use ($product) {
            $query->where('item_type', 'product')
                  ->where('item_id', $product->id);
        })
        ->with([
            'user',
            'postItems',
            'taggedProducts'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => "Posts featuring {$product->name}",
        ]);
    }

    /**
     * Get posts with affiliate items (for affiliate tracking)
     */
    public function getAffiliatePosts(Request $request)
    {
        $user = $request->user();

        if (!$user->isAffiliate()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - Affiliate only',
            ], 403);
        }

        $posts = Post::whereHas('postItems', function ($query) use ($user) {
            $query->where('affiliate_code', $user->affiliate_code);
        })
        ->with([
            'user',
            'postItems' => function ($query) use ($user) {
                $query->where('affiliate_code', $user->affiliate_code);
            },
            'taggedProducts'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $posts,
        ]);
    }

    /**
     * Search posts by caption or user
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $query = $request->query;

        $posts = Post::where('caption', 'like', "%{$query}%")
            ->orWhereHas('user', function ($userQuery) use ($query) {
                $userQuery->where('username', 'like', "%{$query}%")
                         ->orWhere('full_name', 'like', "%{$query}%");
            })
            ->with([
                'user',
                'postItems',
                'taggedProducts'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $posts,
        ]);
    }
}
