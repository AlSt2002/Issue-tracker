@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Tags</h1>

            <a href="{{ route('tags.create') }}"
               style="background-color:#2563eb;color:white;padding:10px 16px;border-radius:6px;display:inline-block;">
                Create Tag
            </a>
        </div>

        {{-- TAG LIST --}}
        <div class="bg-white shadow rounded p-4">
            <h2 class="text-lg font-semibold mb-4">All Tags</h2>

            <div id="tags-list" class="flex flex-wrap gap-2">
                @forelse($tags as $tag)
                    <span class="px-3 py-1 rounded text-white"
                          style="background-color: {{ $tag->color }}">
                    {{ $tag->name }}
                </span>
                @empty
                    <p class="text-gray-500">No tags found.</p>
                @endforelse
            </div>
        </div>

        {{-- CREATE TAG FORM (AJAX) --}}
        <div class="mt-6 bg-white shadow rounded p-4">
            <h2 class="text-lg font-semibold mb-4">Create New Tag</h2>

            <form id="tag-form" action="{{ route('tags.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <input type="text"
                           name="name"
                           placeholder="Tag name"
                           class="w-full border rounded p-2"
                           required>
                </div>

                <div class="mb-3">
                    <input type="color"
                           name="color"
                           class="w-16 h-10 border rounded">
                </div>

                <button type="submit"
                        style="background-color:#2563eb;color:white;padding:10px 16px;border-radius:6px;">
                    Create Tag
                </button>
            </form>
        </div>

    </div>

    {{-- AJAX --}}
    <script>
        document.getElementById('tag-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = e.target;

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: new FormData(form)
            });

            const data = await response.json();
            const tag = data.data;

            document.getElementById('tags-list').insertAdjacentHTML('beforeend', `
        <span class="px-3 py-1 rounded text-white" style="background-color:${tag.color}">
            ${tag.name}
        </span>
    `);

            form.reset();
        });
    </script>

@endsection
