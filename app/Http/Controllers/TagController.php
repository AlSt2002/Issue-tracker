<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Models\Issue;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * Display the tags page (list + create form).
     *
     * Returns JSON when the request expects JSON (AJAX), otherwise renders the view.
     */
    public function index(Request $request): View|JsonResponse
    {
        $tags = Tag::query()
            ->orderBy('name')
            ->get(['id', 'name', 'color']);

        if ($request->wantsJson()) {
            return response()->json(['data' => $tags]);
        }

        return view('tags.index', compact('tags'));
    }


    public function create(): View
    {
        return view('tags.create');
    }
    /**
     * Store a new tag. Used by the AJAX create form.
     */
    public function store(StoreTagRequest $request): JsonResponse|RedirectResponse
    {
        $tag = Tag::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'data'    => $tag->only(['id', 'name', 'color']),
                'message' => 'Tag created successfully.',
            ], 201);
        }

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag created successfully.');
    }

    /**
     * Attach a tag to an issue (AJAX).
     */
    public function attach(Request $request, Issue $issue): JsonResponse
    {
        $validated = $request->validate([
            'tag_id' => ['required', 'integer', 'exists:tags,id'],
        ]);

        $issue->tags()->syncWithoutDetaching([$validated['tag_id']]);

        return response()->json([
            'message' => 'Tag attached.',
            'tags'    => $issue->tags()->get(['tags.id', 'tags.name', 'tags.color']),
        ]);
    }

    /**
     * Detach a tag from an issue (AJAX).
     */
    public function detach(Issue $issue, Tag $tag): JsonResponse
    {
        $issue->tags()->detach($tag->id);

        return response()->json([
            'message' => 'Tag detached.',
            'tags'    => $issue->tags()->get(['tags.id', 'tags.name', 'tags.color']),
        ]);
    }
}
