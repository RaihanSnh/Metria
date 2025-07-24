<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeedController extends Controller
{
    /**
     * Display the social feed.
     */
    public function index()
    {
        Log::info('Feed page accessed');
        
        // Eager load the polymorphic 'items' relationship and the actual 'item' model (e.g., Product)
        $posts = Post::with('user', 'items.item')->latest()->paginate(15);
        
        Log::info('Posts retrieved for feed', [
            'total_posts' => $posts->total(),
            'current_page_posts' => $posts->count()
        ]);

        return view('feed.index', compact('posts'));
    }
}
