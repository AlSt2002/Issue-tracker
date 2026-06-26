@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">

        <!-- Project Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $project->name }}</h1>
                    <p class="text-gray-600">{{ $project->description }}</p>
                </div>

                <div class="flex gap-3 items-center">

                    <!-- EDIT -->
                    <a href="{{ route('projects.edit', $project) }}"
                       style="background-color:#2563eb;color:white;padding:10px 16px;border-radius:6px;display:inline-block;">
                        Edit Project
                    </a>

                    <!-- DELETE -->
                    <form action="{{ route('projects.destroy', $project) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this project?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Delete Project
                        </button>
                    </form>

                    <!-- BACK -->
                    <a href="{{ route('projects.index') }}"
                       class="px-4 py-2 bg-gray-300 text-gray-900 rounded-lg hover:bg-gray-400">
                        Back
                    </a>

                </div>
            </div>

            <!-- Project Details -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Owner</h3>
                    <p class="text-lg text-gray-900">{{ $project->owner?->name ?? 'N/A' }}</p>
                </div>

                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Start Date</h3>
                    <p class="text-lg text-gray-900">{{ $project->start_date?->format('M d, Y') ?? 'Not set' }}</p>
                </div>

                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Deadline</h3>
                    <p class="text-lg text-gray-900">{{ $project->deadline?->format('M d, Y') ?? 'Not set' }}</p>
                </div>

                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Total Issues</h3>
                    <p class="text-lg text-gray-900">{{ $project->issues->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Issues Section -->
        <div class="bg-white rounded-lg shadow">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Issues</h2>

                <a href="{{ route('issues.create', ['project' => $project]) }}"
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    + New Issue
                </a>
            </div>

            @if($project->issues->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Title</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Priority</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Assigned To</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($project->issues as $issue)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">

                                <!-- Title -->
                                <td class="px-6 py-4">
                                    <a href="{{ route('issues.show', $issue) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ $issue->title }}
                                    </a>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                        @switch($issue->status)
                                            @case('open')
                                                bg-blue-100 text-blue-800
                                                @break
                                            @case('in_progress')
                                                bg-yellow-100 text-yellow-800
                                                @break
                                            @case('closed')
                                                bg-green-100 text-green-800
                                                @break
                                            @default
                                                bg-gray-100 text-gray-800
                                        @endswitch
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $issue->status->value)) }}
                                    </span>
                                </td>

                                <!-- Priority -->
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                        @switch($issue->priority)
                                            @case('low')
                                                bg-green-100 text-green-800
                                                @break
                                            @case('medium')
                                                bg-yellow-100 text-yellow-800
                                                @break
                                            @case('high')
                                                bg-red-100 text-red-800
                                                @break
                                            @default
                                                bg-gray-100 text-gray-800
                                        @endswitch
                                    ">
                                        {{ ucfirst($issue->priority->value) }}
                                    </span>
                                </td>

                                <!-- Assigned -->
                                <td class="px-6 py-4">
                                    @if($issue->assignees->count() > 0)
                                        <div class="flex gap-2">
                                            @foreach($issue->users->take(3) as $user)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">
                                                    {{ $user->name }}
                                                </span>
                                            @endforeach

                                            @if($issue->users->count() > 3)
                                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-sm">
                                                    +{{ $issue->users->count() - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-500">Unassigned</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <a href="{{ route('issues.edit', $issue) }}"
                                       class="text-blue-600 hover:text-blue-800">
                                        Edit
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <p class="text-gray-500 text-lg mb-4">No issues yet for this project</p>
                    <a href="{{ route('issues.create', ['project' => $project]) }}"
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Create First Issue
                    </a>
                </div>
            @endif
        </div>

    </div>
@endsection
