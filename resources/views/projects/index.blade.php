
@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
	<div class="flex items-center justify-between mb-6">
		<h1 class="text-2xl font-semibold text-gray-900">Projects</h1>
        <a href="{{ route('projects.create') }}"
           style="background-color:#2563eb;color:white;padding:10px 16px;border-radius:6px;display:inline-block;">
            Create Project
        </a>
	</div>

	@if($projects->isEmpty())
		<div class="bg-white p-6 rounded shadow text-gray-700">
			<p class="mb-4">No projects found.</p>
			<a href="{{ route('projects.create') }}" class="text-sm text-blue-600 hover:underline">Create your first project</a>
		</div>
	@else
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
			@foreach($projects as $project)
				<a href="{{ route('projects.show', $project) }}" class="block bg-white rounded-lg p-5 shadow hover:shadow-md transition">
					<div class="flex items-start justify-between">
						<h2 class="text-lg font-medium text-gray-900">{{ $project->name }}</h2>
						<span class="text-sm text-gray-500">#{{ $project->id }}</span>
					</div>

					@if(!empty($project->description))
						<p class="mt-3 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($project->description, 140) }}</p>
					@endif

					<div class="mt-4 flex items-center justify-between text-sm text-gray-500">
						<div>
							<div>Owner: {{ optional($project->owner)->name ?? '—' }}</div>
							<div>Start: {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->toDateString() : '—' }}</div>
							<div>Deadline: {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->toDateString() : '—' }}</div>
						</div>
						<div class="text-right">
							<span class="inline-block px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $project->issues_count ?? ($project->issues ? $project->issues->count() : 0) }} issues</span>
						</div>
					</div>
				</a>
			@endforeach
		</div>

		<div class="mt-6">
			{{ $projects->links() }}
		</div>
	@endif
</div>
@endsection

