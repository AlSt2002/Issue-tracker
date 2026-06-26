<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Issue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Paginated list of comments for an issue (AJAX).
     */
    public function index(Request $request, Issue $issue): JsonResponse
    {
        $comments = $issue->comments()
            ->latest()
            ->paginate(5);

        return response()->json([
            'data' => $comments->items(),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page'    => $comments->lastPage(),
                'per_page'     => $comments->perPage(),
                'total'        => $comments->total(),
            ],
        ]);
    }

    /**
     * Create a new comment for an issue (AJAX).
     */
    public function store(StoreCommentRequest $request, Issue $issue)
    {
        $comment = $issue->comments()->create([
            'author_name' => Auth::user()->name,
            'body'        => $request->validated('body'),
        ]);


        return response()->json([
            'data' => [
                'id' => $comment->id,
                'body' => $comment->body,
                'author_name' => auth()->user()->name,
                'created_at' => $comment->created_at->diffForHumans(),
            ]
        ]);
    }
}
