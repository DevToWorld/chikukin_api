<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // 一般お知らせのみ抽出
        $query = NewsArticle::published()->where('type', 'news');

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->boolean('featured_only')) {
            $query->featured();
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 12);
        $articles = $query->orderBy('published_at', 'desc')->paginate($perPage);

        return response()->json($articles);
    }

    public function show($slug): JsonResponse
    {
        $article = NewsArticle::published()->where('type', 'news')
            ->where('slug', $slug)
            ->firstOrFail();
        
        $article->incrementViewCount();
        
        return response()->json($article);
    }

    public function categories(): JsonResponse
    {
        $categories = NewsArticle::published()->where('type', 'news')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();
        
        return response()->json($categories);
    }

    public function featured(): JsonResponse
    {
        $articles = NewsArticle::published()->where('type', 'news')
            ->featured()
            ->recent(6)
            ->get();
        
        return response()->json($articles);
    }

    public function related($slug): JsonResponse
    {
        $article = NewsArticle::published()->where('type', 'news')
            ->where('slug', $slug)
            ->firstOrFail();
        
        $related = NewsArticle::published()->where('type', 'news')
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->recent(4)
            ->get();
        
        return response()->json($related);
    }

    public function popular(): JsonResponse
    {
        $articles = NewsArticle::published()->where('type', 'news')
            ->orderBy('view_count', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json($articles);
    }
}
