<?php

namespace App\Http\Controllers;

use App\Enums\IssuePriority;
use App\Enums\IssueStatus;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource, with optional filters
     * by status, priority and tag.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'status'   => ['nullable', 'string', 'in:open,in_progress,closed'],
            'priority' => ['nullable', 'string', 'in:low,medium,high'],
            'tag'      => ['nullable', 'integer', 'exists:tags,id'],
        ]);

        $issues = Issue::query()
            ->with(['project', 'tags'])
            ->when($validated['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($validated['priority'] ?? null, fn ($q, $priority) => $q->where('priority', $priority))
            ->when($validated['tag'] ?? null, function ($q, $tagId) {
                $q->whereHas('tags', fn ($q2) => $q2->where('tags.id', $tagId));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $tags = Tag::query()->orderBy('name')->get();

        return view('issues.index', [
            'issues'   => $issues,
            'tags'     => $tags,
            'statuses' => IssueStatus::cases(),
            'priorities' => IssuePriority::cases(),
            'filters' => [
                'status'   => $validated['status'] ?? null,
                'priority' => $validated['priority'] ?? null,
                'tag'      => $validated['tag'] ?? null,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $projects = Project::query()->orderBy('name')->get();
        $tags     = Tag::query()->orderBy('name')->get();
        $users    = User::query()->orderBy('name')->get();

        return view('issues.create', [
            'projects'   => $projects,
            'tags'       => $tags,
            'users'      => $users,
            'statuses'   => IssueStatus::cases(),
            'priorities' => IssuePriority::cases(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIssueRequest $request): RedirectResponse
    {
        $issue = Issue::create($request->validated());

        if ($request->filled('tags')) {
            $issue->tags()->sync($request->input('tags', []));
        }

        return redirect()
            ->route('issues.show', $issue)
            ->with('success', 'Issue created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue): View
    {
        $issue->load(['project', 'tags', 'assignees', 'comments']);

        return view('issues.show', compact('issue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issue $issue): View
    {
        $issue->load('tags');

        $projects = Project::query()->orderBy('name')->get();
        $tags     = Tag::query()->orderBy('name')->get();

        return view('issues.edit', [
            'issue'      => $issue,
            'projects'   => $projects,
            'tags'       => $tags,
            'statuses'   => IssueStatus::cases(),
            'priorities' => IssuePriority::cases(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIssueRequest $request, Issue $issue): RedirectResponse
    {
        $issue->update($request->validated());

        if ($request->has('tags')) {
            $issue->tags()->sync($request->input('tags', []));
        }

        return redirect()
            ->route('issues.show', $issue)
            ->with('success', 'Issue updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue): RedirectResponse
    {
        $issue->delete();

        return redirect()
            ->route('issues.index')
            ->with('success', 'Issue deleted successfully.');
    }
}
