
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
	<div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">
		<h1 class="text-2xl font-semibold mb-4">Edit Issue</h1>

		@if ($errors->any())
			<div class="mb-4">
				<div class="font-medium text-red-600">Whoops! Something went wrong.</div>
				<ul class="mt-3 list-disc list-inside text-sm text-red-600">
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form method="POST" action="{{ route('issues.update', $issue) }}">
			@csrf
			@method('PATCH')

			<div class="mb-4">
				<label class="block text-sm font-medium text-gray-700" for="title">Title</label>
				<input id="title" name="title" type="text" required
					class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
					value="{{ old('title', $issue->title) }}">
			</div>

			<div class="mb-4">
				<label class="block text-sm font-medium text-gray-700" for="description">Description</label>
				<textarea id="description" name="description" rows="6"
					class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $issue->description) }}</textarea>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
				<div>
					<label class="block text-sm font-medium text-gray-700" for="status">Status</label>
					<select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
						@php
							$currentStatus = old('status', $issue->status);
						@endphp
						<option value="open" {{ $currentStatus === 'open' ? 'selected' : '' }}>Open</option>
						<option value="in_progress" {{ $currentStatus === 'in_progress' ? 'selected' : '' }}>In Progress</option>
						<option value="closed" {{ $currentStatus === 'closed' ? 'selected' : '' }}>Closed</option>
					</select>
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-700" for="priority">Priority</label>
					<select id="priority" name="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
						@php $currentPriority = old('priority', $issue->priority); @endphp
						<option value="low" {{ $currentPriority === 'low' ? 'selected' : '' }}>Low</option>
						<option value="medium" {{ $currentPriority === 'medium' ? 'selected' : '' }}>Medium</option>
						<option value="high" {{ $currentPriority === 'high' ? 'selected' : '' }}>High</option>
					</select>
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-700" for="due_date">Due date</label>
					<input id="due_date" name="due_date" type="date"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
						value="{{ old('due_date', optional($issue->due_date)->format('Y-m-d')) }}">
				</div>
			</div>

			<div class="flex items-center justify-between mt-6">
				<div>
                    <button type="submit" style="background-color:#2563eb;color:white;padding:10px 16px;border-radius:6px;">
                        Save Changes
                    </button>
					<a href="{{ route('issues.show', $issue) }}" class="ml-3 text-sm text-gray-600 hover:underline">Cancel</a>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection

