@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8 px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Issues</h1>
            <a href="{{ route('issues.create') }}"
               style="background-color:#2563eb;color:white;padding:10px 16px;border-radius:6px;display:inline-block;">
                Create Issue
            </a>        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('issues.index') }}" class="bg-white p-4 rounded shadow mb-6 grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm text-gray-700 mb-1">Status</label>
                <select name="status" onchange="this.form.submit()" class="w-full rounded border-gray-300">
                    <option value="">All</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" @selected($filters['status'] === $status->value)>
                            {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1">Priority</label>
                <select name="priority" onchange="this.form.submit()" class="w-full rounded border-gray-300">
                    <option value="">All</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->value }}" @selected($filters['priority'] === $priority->value)>
                            {{ ucfirst($priority->value) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1">Tag</label>
                <select name="tag" onchange="this.form.submit()" class="w-full rounded border-gray-300">
                    <option value="">All</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" @selected((int) $filters['tag'] === $tag->id)>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <a href="{{ route('issues.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Reset</a>
            </div>
        </form>

        @if($issues->isEmpty())
            <div class="bg-white p-6 rounded shadow text-gray-700">
                <p class="mb-4">No issues found.</p>
                <a href="{{ route('issues.create') }}" class="text-sm text-blue-600 hover:underline">Create your first issue</a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">Title</th>
                        <th class="px-4 py-3 text-left">Project</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Priority</th>
                        <th class="px-4 py-3 text-left">Tags</th>
                        <th class="px-4 py-3 text-left">Due</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @foreach($issues as $issue)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('issues.show', $issue) }}" class="text-blue-600 hover:underline">
                                    {{ $issue->title }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ optional($issue->project)->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">
                                    {{ ucfirst(str_replace('_', ' ', $issue->status->value)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">
                                    {{ ucfirst($issue->priority->value) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @foreach($issue->tags as $tag)
                                    <span class="inline-block px-2 py-0.5 rounded text-xs"
                                          style="background-color: {{ $tag->color ?? '#e5e7eb' }}; color: #111827;">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $issue->due_date ? $issue->due_date->toDateString() : '—' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $issues->links() }}
            </div>
        @endif
    </div>
@endsection
