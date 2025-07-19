<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /**
     * Display the social feed.
     */
    public function index()
    {
        // Eager load the polymorphic 'items' relationship and the actual 'item' model (e.g., Product)
        $posts = Post::with('user', 'items.item')->latest()->paginate(15);

        return view('feed.index', compact('posts'));
    }
}
